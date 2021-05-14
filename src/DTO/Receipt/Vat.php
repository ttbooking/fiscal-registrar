<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use Spatie\DataTransferObject\Attributes\CastWith;
use TTBooking\FiscalRegistrar\DTO\Casters\RoundingCaster;
use TTBooking\FiscalRegistrar\DTO\Casters\VatTypeCaster;
use TTBooking\FiscalRegistrar\DTO\DataTransferObject;
use TTBooking\FiscalRegistrar\Enums\VatType;

final class Vat extends DataTransferObject
{
    // 1105, 1104, 1103, 1102, 1107, 1106
    #[CastWith(VatTypeCaster::class)]
    public VatType $type;

    // 1105, 1104, 1103, 1102, 1107, 1106
    #[CastWith(RoundingCaster::class)]
    public float|int $sum;
}
