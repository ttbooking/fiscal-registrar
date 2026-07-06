<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use Spatie\LaravelData\Attributes\WithCast;
use TTBooking\FiscalRegistrar\DTO\Casters\RoundingCaster;
use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class Vats extends DataTransferObject
{
    // 1102
    #[WithCast(RoundingCaster::class)]
    public float|int $vat22 = 0;

    // 1103
    #[WithCast(RoundingCaster::class)]
    public float|int $vat10 = 0;

    // 1104
    #[WithCast(RoundingCaster::class)]
    public float|int $with_vat0 = 0;

    // 1105
    #[WithCast(RoundingCaster::class)]
    public float|int $without_vat = 0;

    // 1106
    #[WithCast(RoundingCaster::class)]
    public float|int $vat122 = 0;

    // 1107
    #[WithCast(RoundingCaster::class)]
    public float|int $vat110 = 0;

    // 1199
    #[WithCast(RoundingCaster::class)]
    public float|int $vat5 = 0;

    // 1199
    #[WithCast(RoundingCaster::class)]
    public float|int $vat7 = 0;

    // 1199
    #[WithCast(RoundingCaster::class)]
    public float|int $vat105 = 0;

    // 1199
    #[WithCast(RoundingCaster::class)]
    public float|int $vat107 = 0;
}
