<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Drivers;

use Closure;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Str;
use Lamoda\AtolClient\Converter\ObjectConverter;
use Lamoda\AtolClient\V4\AtolApi;
use Lamoda\AtolClient\V4\DTO\GetToken as AtolGetToken;
use Lamoda\AtolClient\V4\DTO\Register as AtolRegister;
use Lamoda\AtolClient\V4\DTO\Report as AtolReport;
use Lamoda\AtolClient\V4\DTO\Shared\ErrorType;
use RuntimeException;
use TTBooking\FiscalRegistrar\Contracts\SupportsCallbacks;
use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\DTO\Result;
use TTBooking\FiscalRegistrar\Enums\Operation;
use TTBooking\FiscalRegistrar\Enums\TaxSystem;
use TTBooking\FiscalRegistrar\Exceptions;
use TTBooking\FiscalRegistrar\Support\Driver;

class AtolDriver extends Driver implements SupportsCallbacks
{
    protected AtolApi $api;

    protected ObjectConverter $converter;

    protected Repository $cache;

    public function __construct(
        AtolApiFactory $factory,
        Repository $cache,
        UrlGenerator $urlGenerator,
        array $config = [],
        string $connection = 'default'
    ) {
        parent::__construct($urlGenerator, $config, $connection);
        $this->api = $factory->make($config['url'] ?? null);
        $this->converter = $factory->getConverter();
        $this->cache = $cache;
    }

    public function register(Operation $operation, string $externalId, Receipt $payload): string
    {
        $operationString = Str::camel($operation->getValue());

        $registerRequest = $this->makeRequest($externalId, $payload);

        $force = false;
        do try {
            /** @var AtolRegister\RegisterResponse $registerResponse */
            $registerResponse = $this->api->{$operationString}
            ($this->config['group_code'], $this->getToken($force), $registerRequest);
            $force = true;
        } catch (RuntimeException $e) {
            throw new Exceptions\DriverException("{$operationString} operation failed.", $e->getCode(), $e);
        } while (static::tokenHasExpired($registerResponse));

        return $this->processRegisterResponse($registerResponse);
    }

    public function report(string $id): ?Result
    {
        // TODO: implement

        $force = false;
        do try {
            $reportResponse = $this->api->report($this->config['group_code'], $this->getToken($force), $id);
            $force = true;
        } catch (RuntimeException $e) {
            throw new Exceptions\DriverException('Report operation failed.', $e->getCode(), $e);
        } while (static::tokenHasExpired($reportResponse));

        return $this->processReportResponse($reportResponse);
    }

    public function processCallback(mixed $payload, Closure $handler = null): void
    {
        try {
            $handler && $handler($this->processReportResponse(
                $this->converter->getResponseObject(AtolReport\ReportResponse::class, json_encode($payload))
            ));
        } catch (Exceptions\DriverException $e) {
            // Suppress driver exceptions during callback execution
        }
    }

    /**
     * @param  bool  $force
     * @return string
     *
     * @throws Exceptions\DriverException
     */
    protected function getToken(bool $force = false): string
    {
        $key = "fiscal-registrar:{$this->connection}:token";

        $tokenRetriever = function () {
            $tokenResponse = $this->api->getToken(new AtolGetToken\GetTokenRequest(
                $this->config['login'],
                $this->config['password']
            ));

            if (! is_null($error = $tokenResponse->getError())) {
                throw new Exceptions\DriverException($error->getText(), $error->getCode());
            }

            return $tokenResponse->getToken();
        };

        return $force
            ? tap($tokenRetriever(), fn ($token) => $this->cache->put($key, $token, 86400))
            : $this->cache->remember($key, 86400, $tokenRetriever);
    }

    /**
     * @param  AtolRegister\RegisterResponse|AtolReport\ReportResponse  $atolResponse
     * @return bool
     */
    protected static function tokenHasExpired($atolResponse): bool
    {
        return ! is_null($error = $atolResponse->getError())
            && $error->getType()->equals(ErrorType::SYSTEM())
            && $error->getCode() === 11;
    }

    protected function makeRequest(string $externalId, Receipt $receipt): AtolRegister\RegisterRequest
    {
        $receipt->company->name ??= $this->config['company']['name'] ?? null;
        $receipt->company->tax_system ??= isset($this->config['company']['tax_system'])
            ? new TaxSystem($this->config['company']['tax_system']) : null;
        $receipt->company->payment_address ??= $this->config['company']['payment_address']
            ?? '109316, Регион 77, Москва, Волгоградский проспект, дом 42, корпус 9';

        $registerRequest = new AtolRegister\RegisterRequest(

            $externalId,

            new AtolRegister\Receipt(

                new AtolRegister\Client(
                    $receipt->client->email,
                    $receipt->client->phone
                ),

                (new AtolRegister\Company(
                    $receipt->company->email ??= $this->config['company']['email'] ?? null,
                    $receipt->company->inn ??= $this->config['company']['inn'] ?? null,
                    $receipt->company->payment_site ??= $this->config['company']['payment_site'] ?? null
                ))->setSno(
                    $receipt->company->tax_system
                        ? AtolRegister\Sno::from($receipt->company->tax_system->getValue()) : null
                ),

                collect($receipt->items)->map(function (Receipt\Item $item) {
                    return new AtolRegister\Item(
                        $item->name, $item->price, $item->quantity, $item->sum,
                        AtolRegister\PaymentMethod::from($item->payment_method->getValue()),
                        new AtolRegister\Vat(
                            AtolRegister\VatType::from($item->vat->type->getValue()),
                            $item->getVatSum()
                        )
                    );
                })->all(),

                collect($receipt->payments)
                    ->values()->filter()
                    ->map(function (float|int $sum, int $type) {
                        return new AtolRegister\Payment(AtolRegister\PaymentType::from($type), $sum);
                    })
                    ->all()

                    ?: [new AtolRegister\Payment(AtolRegister\PaymentType::ELECTRONIC(), 0)],

                $receipt->total

            ),

            date_create()

        );

        if ($callbackUrl = $this->getCallbackUrl()) {
            $registerRequest->setService(new AtolRegister\Service($callbackUrl));
        }

        return $registerRequest;
    }

    protected function processRegisterResponse(AtolRegister\RegisterResponse $registerResponse): string
    {
        if (! is_null($error = $registerResponse->getError())) {
            throw new Exceptions\DriverException($error->getText(), $error->getCode());
        }

        return $registerResponse->getUuid();
    }

    protected function processReportResponse(AtolReport\ReportResponse $reportResponse): ?Result
    {
        if (! is_null($error = $reportResponse->getError())) {
            if ($error->getCode() === 34) {
                return null;
            }
            throw new Exceptions\DriverException($error->getText(), $error->getCode());
        }

        $result = new Result(
            internal_id: $reportResponse->getUuid(),
            timestamp: $reportResponse->getTimestamp(),
            status: $reportResponse->getStatus()->getValue(),
            payload: new Result\Payload(
                fiscal_receipt_number: $reportResponse->getPayload()->getFiscalReceiptNumber(),
                shift_number: $reportResponse->getPayload()->getShiftNumber(),
                receipt_datetime: $reportResponse->getPayload()->getReceiptDatetime(),
                total: $reportResponse->getPayload()->getTotal(),
                fn_number: $reportResponse->getPayload()->getFnNumber(),
                ecr_registration_number: $reportResponse->getPayload()->getEcrRegistrationNumber(),
                fiscal_document_number: $reportResponse->getPayload()->getFiscalDocumentNumber(),
                fiscal_document_attribute: $reportResponse->getPayload()->getFiscalDocumentAttribute(),
                fns_site: $reportResponse->getPayload()->getFnsSite(),
                ofd_inn: $reportResponse->getPayload()->getOfdInn(),
                ofd_receipt_url: $reportResponse->getPayload()->getOfdReceiptUrl(),
            ),
            extra: (object) [
                'group_code' => $reportResponse->getGroupCode(),
                'daemon_code' => $reportResponse->getDaemonCode(),
                'device_code' => $reportResponse->getDeviceCode(),
                'callback_url' => $reportResponse->getCallbackUrl(),
            ],
        );

        $result->payload->ofd_receipt_url = $this->getReceiptUrl($result);

        return $result;
    }
}
