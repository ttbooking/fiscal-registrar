<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Contracts;

use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\DTO\Result;
use TTBooking\FiscalRegistrar\Exceptions\FiscalRegistrarException;

interface FiscalRegistrar
{
    /**
     * @param  string  $externalId
     * @param  Receipt  $receipt
     * @return Result
     *
     * @throws FiscalRegistrarException
     */
    public function sell(string $externalId, Receipt $receipt): Result;

    /**
     * @param  string  $externalId
     * @param  Receipt  $receipt
     * @return Result
     *
     * @throws FiscalRegistrarException
     */
    public function sellRefund(string $externalId, Receipt $receipt): Result;

    /**
     * @param  string  $externalId
     * @param  Receipt  $receipt
     * @return Result
     *
     * @throws FiscalRegistrarException
     */
    public function buy(string $externalId, Receipt $receipt): Result;

    /**
     * @param  string  $externalId
     * @param  Receipt  $receipt
     * @return Result
     *
     * @throws FiscalRegistrarException
     */
    public function buyRefund(string $externalId, Receipt $receipt): Result;

    /**
     * @param  string  $id
     * @return Result
     *
     * @throws FiscalRegistrarException
     */
    public function report(string $id): Result;

    /**
     * @param  mixed  $payload
     * @return mixed
     */
    public function processCallback($payload);
}
