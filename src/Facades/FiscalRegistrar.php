<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Facades;

use Illuminate\Support\Facades\Facade;

class FiscalRegistrar extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'fiscal-registrar';
    }
}
