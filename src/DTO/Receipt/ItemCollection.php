<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use Illuminate\Support\Collection;
use Spatie\DataTransferObject\Attributes\CastWith;
use TTBooking\FiscalRegistrar\DTO\Casters\ItemCollectionCaster;

/**
 * @method \TTBooking\FiscalRegistrar\DTO\Receipt\Item offsetGet(mixed $key)
 */
#[CastWith(ItemCollectionCaster::class)]
class ItemCollection extends Collection
{
}
