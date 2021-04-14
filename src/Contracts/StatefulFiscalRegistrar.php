<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Contracts;

use TTBooking\FiscalRegistrar\DTO;
use TTBooking\FiscalRegistrar\Enums\Operation;
use TTBooking\FiscalRegistrar\Exceptions\DriverException;

interface StatefulFiscalRegistrar extends FiscalRegistrar
{
    /**
     * @param  Operation|null  $operation
     * @param  string|null  $externalId
     * @param  DTO\Receipt|null  $data
     * @return DTO\Result
     *
     * @throws DriverException
     */
    public function register(Operation $operation = null, string $externalId = null, DTO\Receipt $data = null): DTO\Result;

    /**
     * @param  string|null  $id
     * @param  bool  $force
     * @return DTO\Result
     *
     * @throws DriverException
     */
    public function report(string $id = null, bool $force = false): DTO\Result;
}
