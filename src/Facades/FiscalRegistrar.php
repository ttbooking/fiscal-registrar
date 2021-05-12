<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Facades;

use Closure;
use Illuminate\Support\Facades\Facade;
use TTBooking\FiscalRegistrar\Contracts\FiscalRegistrar as FiscalRegistrarContract;
use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\DTO\Result;
use TTBooking\FiscalRegistrar\Enums\Operation;

/**
 * @method static string getConnectionName
 * @method static FiscalRegistrarContract connection(string $name = null)
 * @method static FiscalRegistrarContract[] getConnections
 * @method static string register(Operation $operation, string $externalId, Receipt $receipt)
 * @method static Result|null report(string $id)
 * @method static void processCallback(mixed $payload, Closure $handler = null)
 * @method static Closure connectionResolver
 */
class FiscalRegistrar extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'fiscal-registrar';
    }
}
