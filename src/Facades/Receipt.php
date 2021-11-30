<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Facades;

use Illuminate\Support\Facades\Facade;
use TTBooking\FiscalRegistrar\Contracts\Receipt as ReceiptContract;
use TTBooking\FiscalRegistrar\DTO;

/**
 * @method static ReceiptContract make(DTO\Receipt $payload)
 * @method static ReceiptContract resolve(mixed $id)
 */
class Receipt extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'fiscal-registrar.receipt';
    }
}
