<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt\Item;

use Spatie\DataTransferObject\Attributes\CastWith;
use TTBooking\FiscalRegistrar\DTO\Casters\RoundingCaster;
use TTBooking\FiscalRegistrar\DTO\Casters\VATTypeCaster;
use TTBooking\FiscalRegistrar\DTO\DataTransferObject;
use TTBooking\FiscalRegistrar\Enums\VATType;

final class VAT extends DataTransferObject
{
    // 1199
    #[CastWith(VATTypeCaster::class)]
    public VATType $type;

    // 1200
    #[CastWith(RoundingCaster::class)]
    public float|int|null $sum = null;
}
