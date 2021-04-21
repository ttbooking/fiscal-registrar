<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use Illuminate\Support\Collection;
use Spatie\DataTransferObject\Attributes\CastWith;
use TTBooking\FiscalRegistrar\DTO\Casters\VATCollectionCaster;

/**
 * @method \TTBooking\FiscalRegistrar\DTO\Receipt\VAT offsetGet(mixed $key)
 */
#[CastWith(VATCollectionCaster::class)]
class VATCollection extends Collection
{
}
