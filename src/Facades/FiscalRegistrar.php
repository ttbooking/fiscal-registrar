<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Facades;

use Illuminate\Support\Facades\Facade;
use TTBooking\FiscalRegistrar\Contracts\FiscalRegistrar as FiscalRegistrarContract;
use TTBooking\FiscalRegistrar\DTO\Register\Request;
use TTBooking\FiscalRegistrar\DTO\Register\Response;

/**
 * @method static FiscalRegistrarContract connection(string $name = null)
 * @method static FiscalRegistrarContract[] getConnections()
 * @method static Response sell(string $externalId, Request\Receipt $receipt)
 * @method static Response sellRefund(string $externalId, Request\Receipt $receipt)
 * @method static Response buy(string $externalId, Request\Receipt $receipt)
 * @method static Response buyRefund(string $externalId, Request\Receipt $receipt)
 * @method static object report(string $id)
 * @method static mixed processCallback(mixed $payload)
 */
class FiscalRegistrar extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'fiscal-registrar';
    }
}
