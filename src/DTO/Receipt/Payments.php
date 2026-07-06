<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use Spatie\LaravelData\Attributes\WithCast;
use TTBooking\FiscalRegistrar\DTO\Casters\RoundingCaster;
use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class Payments extends DataTransferObject
{
    // 1031
    #[WithCast(RoundingCaster::class)]
    public float|int $cash = 0;

    // 1081
    #[WithCast(RoundingCaster::class)]
    public float|int $electronic = 0;

    // 1215
    #[WithCast(RoundingCaster::class)]
    public float|int $prepaid = 0;

    // 1216
    #[WithCast(RoundingCaster::class)]
    public float|int $postpaid = 0;

    // 1217
    #[WithCast(RoundingCaster::class)]
    public float|int $other = 0;
}
