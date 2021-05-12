<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Facades;

use Closure;
use Illuminate\Support\Facades\Facade;
use TTBooking\FiscalRegistrar\Contracts\FiscalRegistrar as FiscalRegistrarContract;
use TTBooking\FiscalRegistrar\DTO;
use TTBooking\FiscalRegistrar\Enums\Operation;
use TTBooking\FiscalRegistrar\Models\Receipt;

/**
 * @method static string getConnectionName
 * @method static FiscalRegistrarContract connection(string $name = null)
 * @method static FiscalRegistrarContract[] getConnections
 * @method static string register(Operation $operation, string $externalId, DTO\Receipt $receipt)
 * @method static DTO\Result|null report(string $id)
 * @method static void processCallback(mixed $payload, Closure $handler = null)
 * @method static Closure connectionResolver
 * @method static Closure idGenerator
 * @method static string generateId(Receipt $receipt)
 */
class FiscalRegistrar extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'fiscal-registrar';
    }
}
