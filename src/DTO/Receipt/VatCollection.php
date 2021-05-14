<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use Illuminate\Support\Collection;
use Spatie\DataTransferObject\Attributes\CastWith;
use TTBooking\FiscalRegistrar\DTO\Casters\VatCollectionCaster;

/**
 * @method \TTBooking\FiscalRegistrar\DTO\Receipt\Vat offsetGet(mixed $key)
 */
#[CastWith(VatCollectionCaster::class)]
class VatCollection extends Collection
{
}
