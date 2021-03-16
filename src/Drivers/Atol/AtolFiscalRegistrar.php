<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Drivers\Atol;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Str;
use Lamoda\AtolClient\V4\AtolApi;
use Lamoda\AtolClient\V4\DTO\GetToken as AtolGetToken;
use Lamoda\AtolClient\V4\DTO\Register as AtolRegister;
use Lamoda\AtolClient\V4\DTO\Report as AtolReport;
use Lamoda\AtolClient\V4\DTO\Shared\ErrorType;
use RuntimeException;
use TTBooking\FiscalRegistrar\Drivers\FiscalRegistrar;
use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\DTO\Result;
use TTBooking\FiscalRegistrar\Exceptions;

class AtolFiscalRegistrar extends FiscalRegistrar
{
    protected AtolApi $api;

    protected Repository $cache;

    public function __construct(ApiFactory $factory, Repository $cache, array $config = [], string $connection = 'default')
    {
        parent::__construct($config, $connection);
        $this->api = $factory->make($config['url']);
        $this->cache = $cache;
    }

    public function sell(string $externalId, Receipt $receipt): Result
    {
        return $this->register(__FUNCTION__, $externalId, $receipt);
    }

    public function sellRefund(string $externalId, Receipt $receipt): Result
    {
        return $this->register(__FUNCTION__, $externalId, $receipt);
    }

    public function buy(string $externalId, Receipt $receipt): Result
    {
        return $this->register(__FUNCTION__, $externalId, $receipt);
    }

    public function buyRefund(string $externalId, Receipt $receipt): Result
    {
        return $this->register(__FUNCTION__, $externalId, $receipt);
    }

    public function report(string $id): Result
    {
        // TODO: implement

        $force = false;
        do try {
            $reportResponse = $this->api->report($this->config['group_code'], $this->getToken($force), $id);
            $force = true;
        } catch (RuntimeException $e) {
            throw new Exceptions\FiscalRegistrarException('Report operation failed.', $e->getCode(), $e);
        } while (static::tokenHasExpired($reportResponse));

        return $this->processReportResponse($reportResponse);
    }

    public function processCallback($payload)
    {
        return $this->convertPayload($payload);
    }

    /**
     * @param  bool  $force
     * @return string
     *
     * @throws Exceptions\FiscalRegistrarException
     */
    protected function getToken(bool $force = false): string
    {
        $key = "fiscal-registrar::{$this->connection}::token";

        $tokenRetriever = function () {
            $tokenResponse = $this->api->getToken(new AtolGetToken\GetTokenRequest(
                $this->config['login'],
                $this->config['password']
            ));

            if (! is_null($error = $tokenResponse->getError())) {
                throw new Exceptions\FiscalRegistrarException($error->getText(), $error->getCode());
            }

            return $tokenResponse->getToken();
        };

        return $force
            ? tap($tokenRetriever(), fn ($token) => $this->cache->put($key, $token, 86400))
            : $this->cache->remember($key, 86400, $tokenRetriever);
    }

    /**
     * @param  string  $operation
     * @param  string  $externalId
     * @param  Receipt  $receipt
     * @return Result
     *
     * @throws Exceptions\FiscalRegistrarException
     */
    protected function register(string $operation, string $externalId, Receipt $receipt): Result
    {
        $registerRequest = $this->makeRequest($externalId, $receipt);

        $eventClass = static::eventClass($operation);
        $this->event(new $eventClass($receipt));

        $force = false;
        do try {
            /** @var AtolRegister\RegisterResponse $registerResponse */
            $registerResponse = $this->api->{$operation}
                ($this->config['group_code'], $this->getToken($force), $registerRequest);
            $force = true;
        } catch (RuntimeException $e) {
            throw new Exceptions\FiscalRegistrarException("{$operation} operation failed.", $e->getCode(), $e);
        } while (static::tokenHasExpired($registerResponse));

        $eventClass = static::eventClass($operation, true);
        $this->event(new $eventClass($receipt));

        return $this->processRegisterResponse($registerResponse);
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

    protected static function eventClass(string $operation, bool $registered = false): string
    {
        $stage = $registered ? 'Registered' : 'Registering';

        return 'TTBooking\\FiscalRegistrar\\Events\\'.Str::studly($operation).'Receipt'.$stage;
    }

    protected function makeRequest(string $externalId, Receipt $receipt): AtolRegister\RegisterRequest
    {
        $registerRequest = new AtolRegister\RegisterRequest(

            $externalId,

            new AtolRegister\Receipt(

                new AtolRegister\Client(
                    $receipt->client->email,
                    $receipt->client->phone
                ),

                new AtolRegister\Company(
                    $receipt->company->email ?? $this->config['email'],
                    $receipt->company->inn ?? $this->config['inn'],
                    $receipt->company->paymentAddress ?? $this->config['payment_address']
                ),

                $receipt->items->map(function (Receipt\Item $item) {
                    return new AtolRegister\Item(
                        $item->name, $item->price, $item->quantity, $item->sum,
                        AtolRegister\PaymentMethod::from($item->paymentMethod),
                        new AtolRegister\Vat(AtolRegister\VatType::from($item->vat->type), $item->vat->sum)
                    );
                })->all(),

                $receipt->payments->map(function (Receipt\Payment $payment) {
                    return new AtolRegister\Payment(AtolRegister\PaymentType::from($payment->type), $payment->sum);
                })->all(),

                $receipt->total

            ),

            date_create()

        );

        if (isset($this->config['callback_url'])) {
            $registerRequest->setService(new AtolRegister\Service($this->config['callback_url']));
        }

        return $registerRequest;
    }

    protected function processRegisterResponse(AtolRegister\RegisterResponse $registerResponse): Result
    {
        if (! is_null($error = $registerResponse->getError())) {
            throw new Exceptions\FiscalRegistrarException($error->getText(), $error->getCode());
        }

        return new Result(
            '',
            $registerResponse->getUuid(),
            $registerResponse->getTimestamp(),
            $registerResponse->getStatus()->getValue(),
        );
    }

    protected function processReportResponse(AtolReport\ReportResponse $reportResponse): Result
    {
        return new Result(
            '',
            $reportResponse->getUuid(),
            $reportResponse->getTimestamp(),
            $reportResponse->getStatus()->getValue(),
            new Result\Payload(
                $reportResponse->getPayload()->getFiscalReceiptNumber(),
                $reportResponse->getPayload()->getShiftNumber(),
                $reportResponse->getPayload()->getReceiptDatetime(),
                $reportResponse->getPayload()->getTotal(),
                $reportResponse->getPayload()->getFnNumber(),
                $reportResponse->getPayload()->getEcrRegistrationNumber(),
                $reportResponse->getPayload()->getFiscalDocumentNumber(),
                $reportResponse->getPayload()->getFiscalDocumentAttribute(),
                $reportResponse->getPayload()->getFnsSite()
            ),
            (object) [
                'group_code' => $reportResponse->getGroupCode(),
                'daemon_code' => $reportResponse->getDaemonCode(),
                'device_code' => $reportResponse->getDeviceCode(),
                'callback_url' => $reportResponse->getCallbackUrl(),
                //'ofd_receipt_url' => $reportResponse->getPayload()->getOfdReceiptUrl(),
            ]
        );
    }
}
