<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt\Item;

use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Casters\EnumCaster;
use TTBooking\FiscalRegistrar\DTO\Casters\RoundingCaster;
use TTBooking\FiscalRegistrar\DTO\DataTransferObject;
use TTBooking\FiscalRegistrar\Enums\VatType;

final class Vat extends DataTransferObject
{
    // 1199
    #[CastWith(EnumCaster::class, VatType::class)]
    public VatType $type;

    // 1200
    #[CastWith(RoundingCaster::class)]
    public float|int|null $sum = null;
}
