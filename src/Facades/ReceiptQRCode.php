<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Facades;

use Endroid\QrCode\Writer\Result\ResultInterface;
use Illuminate\Support\Facades\Facade;
use TTBooking\FiscalRegistrar\DTO\Result\Payload;
use TTBooking\FiscalRegistrar\Enums\Operation;

/**
 * @method static ResultInterface make(Payload $payload, Operation $operation)
 */
class ReceiptQRCode extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'fiscal-registrar.qr-code';
    }
}
