<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar;

use Lamoda\AtolClient\V4\AtolApi;
use Lamoda\AtolClient\V4\DTO\Register as AtolRegister;
use RuntimeException;
use TTBooking\FiscalRegistrar\DTO\Register\Request;
use TTBooking\FiscalRegistrar\DTO\Register\Response;

class AtolFiscalRegistrar extends FiscalRegistrar
{
    protected AtolApi $api;

    public function __construct(AtolApi $api, array $config = [])
    {
        parent::__construct($config);
        $this->api = $api;
    }

    public function sell(string $externalId, Request\Receipt $receipt): Response
    {
        return $this->register(__FUNCTION__, $externalId, $receipt);
    }

    public function sellRefund(string $externalId, Request\Receipt $receipt): Response
    {
        return $this->register(__FUNCTION__, $externalId, $receipt);
    }

    public function buy(string $externalId, Request\Receipt $receipt): Response
    {
        return $this->register(__FUNCTION__, $externalId, $receipt);
    }

    public function buyRefund(string $externalId, Request\Receipt $receipt): Response
    {
        return $this->register(__FUNCTION__, $externalId, $receipt);
    }

    /**
     * @param  string  $operation
     * @param  string  $externalId
     * @param  Request\Receipt  $receipt
     * @return Response
     *
     * @throws Exceptions\FiscalRegistrarException
     */
    protected function register(string $operation, string $externalId, Request\Receipt $receipt): Response
    {
        $service = isset($config['callback_url']) ? new Request\Service($config['callback_url']) : null;
        $request = new Request($externalId, $receipt, $service);

        $atolRequest = $this->convertRequest($request);

        try {
            $atolResponse = $this->api->{$operation}($config['group_code'], '', $atolRequest);
        } catch (RuntimeException $e) {
            throw new Exceptions\FiscalRegistrarException("{$operation} operation failed.", $e->getCode(), $e);
        }

        return $this->convertResponse($atolResponse);
    }

    protected function convertRequest(Request $request): AtolRegister\RegisterRequest
    {
        $atolRequest = new AtolRegister\RegisterRequest(

            $request->externalId,

            new AtolRegister\Receipt(

                new AtolRegister\Client(
                    $request->receipt->client->email,
                    $request->receipt->client->phone
                ),

                new AtolRegister\Company(
                    $request->receipt->company->email,
                    $request->receipt->company->inn,
                    $request->receipt->company->paymentAddress
                ),

                $request->receipt->items->map(function (Request\Receipt\Item $item) {
                    return new AtolRegister\Item(
                        $item->name, $item->price, $item->quantity, $item->sum,
                        AtolRegister\PaymentMethod::from($item->paymentMethod),
                        new AtolRegister\Vat(AtolRegister\VatType::from($item->vat->type), $item->vat->sum)
                    );
                })->all(),

                $request->receipt->payments->map(function (Request\Receipt\Payment $payment) {
                    return new AtolRegister\Payment(AtolRegister\PaymentType::from($payment->type), $payment->sum);
                })->all(),

                $request->receipt->total

            ),

            $request->timestamp

        );

        if (isset($request->service->callbackUrl)) {
            $atolRequest->setService(new AtolRegister\Service($request->service->callbackUrl));
        }

        return $atolRequest;
    }

    protected function convertResponse(AtolRegister\RegisterResponse $atolResponse): Response
    {
        return new Response(
            $atolResponse->getUuid(),
            $atolResponse->getTimestamp(),
            $atolResponse->getStatus()->getValue(),
            ! is_null($error = $atolResponse->getError()) ? new Response\Error(
                $error->getCode(),
                $error->getErrorId(),
                $error->getText(),
                $error->getType()->getValue()
            ) : $error
        );
    }
}
