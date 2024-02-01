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
     * @throws DriverException
     */
    public function register(Operation $operation, string $externalId, Receipt $payload): string;

    /**
     * @throws DriverException
     */
    public function report(string $id): ?Result;
}
