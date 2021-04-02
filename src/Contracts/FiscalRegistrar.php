<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Contracts;

use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\DTO\Result;
use TTBooking\FiscalRegistrar\Exceptions\DriverException;

interface FiscalRegistrar
{
    /**
     * @param  string  $externalId
     * @param  Receipt  $data
     * @return Result
     *
     * @throws DriverException
     */
    public function sell(string $externalId, Receipt $data): Result;

    /**
     * @param  string  $externalId
     * @param  Receipt  $data
     * @return Result
     *
     * @throws DriverException
     */
    public function sellRefund(string $externalId, Receipt $data): Result;

    /**
     * @param  string  $externalId
     * @param  Receipt  $data
     * @return Result
     *
     * @throws DriverException
     */
    public function buy(string $externalId, Receipt $data): Result;

    /**
     * @param  string  $externalId
     * @param  Receipt  $data
     * @return Result
     *
     * @throws DriverException
     */
    public function buyRefund(string $externalId, Receipt $data): Result;

    /**
     * @param  string  $id
     * @return Result
     *
     * @throws DriverException
     */
    public function report(string $id): Result;

    /**
     * @param  mixed  $payload
     * @return Result
     */
    public function processCallback($payload): Result;
}
