<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Contracts;

use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\DTO\Result;
use TTBooking\FiscalRegistrar\Enums\Operation;
use TTBooking\FiscalRegistrar\Exceptions\DriverException;

interface StatefulFiscalRegistrar extends FiscalRegistrar
{
    /**
     * @param  Operation|null  $operation
     * @param  string|null  $externalId
     * @param  Receipt|null  $data
     * @return string
     *
     * @throws DriverException
     */
    public function register(Operation $operation = null, string $externalId = null, Receipt $data = null): string;

    /**
     * @param  string|null  $id
     * @param  bool  $force
     * @return Result|null
     *
     * @throws DriverException
     */
    public function report(string $id = null, bool $force = false): ?Result;
}
