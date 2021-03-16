<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Facades;

use Illuminate\Support\Facades\Facade;
use TTBooking\FiscalRegistrar\Contracts\FiscalRegistrar as FiscalRegistrarContract;
use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\DTO\Result;

/**
 * @method static FiscalRegistrarContract connection(string $name = null)
 * @method static FiscalRegistrarContract[] getConnections()
 * @method static Result sell(string $externalId, Receipt $receipt)
 * @method static Result sellRefund(string $externalId, Receipt $receipt)
 * @method static Result buy(string $externalId, Receipt $receipt)
 * @method static Result buyRefund(string $externalId, Receipt $receipt)
 * @method static Result report(string $id)
 * @method static mixed processCallback(mixed $payload)
 */
class FiscalRegistrar extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'fiscal-registrar';
    }
}
