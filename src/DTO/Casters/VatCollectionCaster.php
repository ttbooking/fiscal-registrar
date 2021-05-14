<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Casters;

use Spatie\DataTransferObject\Caster;
use TTBooking\FiscalRegistrar\DTO\Receipt\Vat;
use TTBooking\FiscalRegistrar\DTO\Receipt\VatCollection;

class VatCollectionCaster implements Caster
{
    public function cast(mixed $value): ?VatCollection
    {
        return isset($value)
            ? new VatCollection(
                array_map(fn (Vat|array $data) => $data instanceof Vat ? $data : new Vat(...$data), $value)
            )
            : null;
    }
}
