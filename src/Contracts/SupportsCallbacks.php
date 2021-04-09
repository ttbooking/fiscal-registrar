<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Contracts;

use TTBooking\FiscalRegistrar\DTO\Result;

interface SupportsCallbacks
{
    /**
     * @param  mixed  $payload
     * @return Result
     */
    public function processCallback($payload): Result;
}
