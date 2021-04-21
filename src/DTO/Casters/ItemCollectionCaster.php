<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Casters;

use Spatie\DataTransferObject\Caster;
use TTBooking\FiscalRegistrar\DTO\Receipt\Item;
use TTBooking\FiscalRegistrar\DTO\Receipt\ItemCollection;

class ItemCollectionCaster implements Caster
{
    public function cast(mixed $value): ?ItemCollection
    {
        return isset($value)
            ? new ItemCollection(
                array_map(fn (Item|array $data) => $data instanceof Item ? $data : new Item(...$data), $value)
            )
            : null;
    }
}
