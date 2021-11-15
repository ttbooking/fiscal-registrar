<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Facades;

use Illuminate\Support\Facades\Facade;
use TTBooking\FiscalRegistrar\DTO\Result\Payload;
use TTBooking\FiscalRegistrar\Enums\Operation;

/**
 * @method static string make(Payload $payload, Operation $operation)
 */
class ReceiptQRCode extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'fiscal-registrar.qr-code';
    }
}
