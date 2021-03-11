<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Facades;

use Illuminate\Support\Facades\Facade;
use TTBooking\FiscalRegistrar\DTO\Register\Request;
use TTBooking\FiscalRegistrar\DTO\Register\Response;

/**
 * @method static Response sell(string $externalId, Request\Receipt $receipt)
 * @method static Response sellRefund(string $externalId, Request\Receipt $receipt)
 * @method static Response buy(string $externalId, Request\Receipt $receipt)
 * @method static Response buyRefund(string $externalId, Request\Receipt $receipt)
 */
class FiscalRegistrar extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'fiscal-registrar';
    }
}
