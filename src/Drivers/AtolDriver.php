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
        $operationString = Str::camel($operation->value);

        $registerRequest = $this->makeRequest($externalId, $payload);

        $force = false;
        do {
            try {
                /** @var AtolRegister\RegisterResponse $registerResponse */
                $registerResponse = $this->api->{$operationString}
                ($this->config['group_code'], $this->getToken($force), $registerRequest);
                $force = true;
            } catch (RuntimeException $e) {
                throw new Exceptions\DriverException("$operationString operation failed.", $e->getCode(), $e);
            }
        } while (static::tokenHasExpired($registerResponse));

        return $this->processRegisterResponse($registerResponse);
    }

    public function report(string $id): ?Result
    {
        // TODO: implement

        $force = false;
        do {
            try {
                $reportResponse = $this->api->report($this->config['group_code'], $this->getToken($force), $id);
                $force = true;
            } catch (RuntimeException $e) {
                throw new Exceptions\DriverException('Report operation failed.', $e->getCode(), $e);
            }
        } while (static::tokenHasExpired($reportResponse));

        return $this->processReportResponse($reportResponse);
    }

    public function processCallback(mixed $payload, ?Closure $handler = null): void
    {
        try {
            $handler && $handler($this->processReportResponse(
                $this->converter->getResponseObject(AtolReport\ReportResponse::class, json_encode($payload))
            ));
        } catch (Exceptions\DriverException) {
            // Suppress driver exceptions during callback execution
        }
    }

    /**
     * @throws Exceptions\DriverException
     */
    protected function getToken(bool $force = false): string
    {
        $key = "fiscal-registrar:$this->connection:token";

        $tokenRetriever = function () {
            $tokenResponse = $this->api->getToken(new AtolGetToken\GetTokenRequest(
                $this->config['login'],
                $this->config['password']
            ));

            if (! is_null($error = $tokenResponse->error)) {
                throw new Exceptions\DriverException($error->text, $error->code);
            }

            return $tokenResponse->token;
        };

        return $force
            ? tap($tokenRetriever(), fn ($token) => $this->cache->put($key, $token, 86400))
            : $this->cache->remember($key, 86400, $tokenRetriever);
    }

    /**
     * @param  AtolRegister\RegisterResponse|AtolReport\ReportResponse  $atolResponse
     */
    protected static function tokenHasExpired($atolResponse): bool
    {
        return ! is_null($error = $atolResponse->error)
            && $error->type === ErrorType::System
            && $error->code === 11;
    }

    protected function makeRequest(string $externalId, Receipt $receipt): AtolRegister\RegisterRequest
    {
        $receipt->company->name ??= $this->config['company']['name'] ?? null;
        $receipt->company->tax_system ??= $this->config['company']['tax_system'] ?? null;
        $receipt->company->payment_address ??= $this->config['company']['payment_address']
            ?? '109316, Регион 77, Москва, Волгоградский проспект, дом 42, корпус 9';

        return new AtolRegister\RegisterRequest(

            $externalId,

            new AtolRegister\Receipt(

                new AtolRegister\Client(
                    $receipt->client->email,
                    $receipt->client->phone
                ),

                new AtolRegister\Company(
                    $receipt->company->email ??= $this->config['company']['email'] ?? null,
                    $receipt->company->inn ??= $this->config['company']['inn'] ?? null,
                    $receipt->company->payment_site ??= $this->config['company']['payment_site'] ?? null,
                    $receipt->company->tax_system ? AtolRegister\Sno::from($receipt->company->tax_system->value) : null
                ),

                collect($receipt->items)->map(function (Receipt\Item $item) {
                    return new AtolRegister\Item(
                        $item->name, $item->price, $item->quantity, $item->sum,
                        AtolRegister\PaymentMethod::from($item->payment_method->value),
                        new AtolRegister\Vat(
                            AtolRegister\VatType::from($item->vat->type->value),
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

                    ?: [new AtolRegister\Payment(AtolRegister\PaymentType::Electronic, 0)],

                $receipt->total

            ),

            date_create(),

            ($callbackUrl = $this->getCallbackUrl()) ? new AtolRegister\Service($callbackUrl) : null

        );
    }

    protected function processRegisterResponse(AtolRegister\RegisterResponse $registerResponse): string
    {
        if (! is_null($error = $registerResponse->error)) {
            throw new Exceptions\DriverException($error->text, $error->code);
        }

        return $registerResponse->uuid;
    }

    protected function processReportResponse(AtolReport\ReportResponse $reportResponse): ?Result
    {
        if (! is_null($error = $reportResponse->error)) {
            if ($error->code === 34) {
                return null;
            }
            throw new Exceptions\DriverException($error->text, $error->code);
        }

        $result = new Result(
            internal_id: $reportResponse->uuid,
            timestamp: $reportResponse->timestamp,
            status: $reportResponse->status->value,
            payload: new Result\Payload(
                fiscal_receipt_number: $reportResponse->payload->fiscalReceiptNumber,
                shift_number: $reportResponse->payload->shiftNumber,
                receipt_datetime: $reportResponse->payload->receiptDatetime,
                total: $reportResponse->payload->total,
                fn_number: $reportResponse->payload->fnNumber,
                ecr_registration_number: $reportResponse->payload->ecrRegistrationNumber,
                fiscal_document_number: $reportResponse->payload->fiscalDocumentNumber,
                fiscal_document_attribute: $reportResponse->payload->fiscalDocumentAttribute,
                fns_site: $reportResponse->payload->fnsSite,
                ofd_inn: $reportResponse->payload->ofdInn,
                ofd_receipt_url: $reportResponse->payload->ofdReceiptUrl,
            ),
            extra: (object) [
                'group_code' => $reportResponse->groupCode,
                'daemon_code' => $reportResponse->daemonCode,
                'device_code' => $reportResponse->deviceCode,
                'callback_url' => $reportResponse->callbackUrl,
            ],
        );

        $result->payload->ofd_receipt_url = $this->getReceiptUrl($result);

        return $result;
    }
}
