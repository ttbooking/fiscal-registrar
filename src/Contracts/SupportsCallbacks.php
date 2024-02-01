<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Contracts;

use Closure;
use TTBooking\FiscalRegistrar\DTO\Result;

interface SupportsCallbacks
{
    /**
     * @param  null|Closure(Result):void  $handler
     */
    public function processCallback(mixed $payload, ?Closure $handler = null): void;
}
