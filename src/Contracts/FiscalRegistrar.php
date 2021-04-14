<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Contracts;

use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\DTO\Result;
use TTBooking\FiscalRegistrar\Enums\Operation;
use TTBooking\FiscalRegistrar\Exceptions\DriverException;

interface FiscalRegistrar
{
    /**
     * @param  Operation  $operation
     * @param  string  $externalId
     * @param  Receipt  $data
     * @return string
     *
     * @throws DriverException
     */
    public function register(Operation $operation, string $externalId, Receipt $data): string;

    /**
     * @param  string  $id
     * @return Result|null
     *
     * @throws DriverException
     */
    public function report(string $id): ?Result;
}
