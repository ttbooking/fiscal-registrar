<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Contracts;

use TTBooking\FiscalRegistrar\DTO;
use TTBooking\FiscalRegistrar\Enums\Operation;
use TTBooking\FiscalRegistrar\Exceptions\DriverException;

interface Receipt extends FiscalRegistrar
{
    /**
     * @param  string  $connection
     * @return $this
     */
    public function for(string $connection);

    /**
     * @param  Operation  $operation
     * @return $this
     */
    public function do(Operation $operation);

    /**
     * @param  string|null  $externalId
     * @param  DTO\Receipt|null  $data
     * @return DTO\Result
     *
     * @throws DriverException
     */
    public function sell(string $externalId = null, DTO\Receipt $data = null): DTO\Result;

    /**
     * @param  string|null  $externalId
     * @param  DTO\Receipt|null  $data
     * @return DTO\Result
     *
     * @throws DriverException
     */
    public function sellRefund(string $externalId = null, DTO\Receipt $data = null): DTO\Result;

    /**
     * @param  string|null  $externalId
     * @param  DTO\Receipt|null  $data
     * @return DTO\Result
     *
     * @throws DriverException
     */
    public function buy(string $externalId = null, DTO\Receipt $data = null): DTO\Result;

    /**
     * @param  string|null  $externalId
     * @param  DTO\Receipt|null  $data
     * @return DTO\Result
     *
     * @throws DriverException
     */
    public function buyRefund(string $externalId = null, DTO\Receipt $data = null): DTO\Result;

    /**
     * @param  string|null  $id
     * @return DTO\Result
     *
     * @throws DriverException
     */
    public function report(string $id = null): DTO\Result;

    /**
     * @param  mixed  $payload
     * @return DTO\Result
     */
    public function processCallback($payload): DTO\Result;
}
