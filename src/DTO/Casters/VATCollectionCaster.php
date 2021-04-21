<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Casters;

use Spatie\DataTransferObject\Caster;
use TTBooking\FiscalRegistrar\DTO\Receipt\VAT;
use TTBooking\FiscalRegistrar\DTO\Receipt\VATCollection;

class VATCollectionCaster implements Caster
{
    public function cast(mixed $value): ?VATCollection
    {
        return isset($value)
            ? new VATCollection(
                array_map(fn (VAT|array $data) => $data instanceof VAT ? $data : new VAT(...$data), $value)
            )
            : null;
    }
}
