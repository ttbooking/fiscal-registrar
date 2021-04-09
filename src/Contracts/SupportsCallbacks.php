<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Contracts;

use TTBooking\FiscalRegistrar\DTO\Result;

interface SupportsCallbacks
{
    /**
     * @param  mixed  $payload
     * @return Result|null
     */
    public function processCallback($payload): ?Result;
}
