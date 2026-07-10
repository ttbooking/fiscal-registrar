<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use Spatie\LaravelData\Attributes\WithCast;
use TTBooking\FiscalRegistrar\DTO\Casters\RoundingCaster;
use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class Vats extends DataTransferObject
{
    public function __construct(
        // 1102
        #[WithCast(RoundingCaster::class)]
        public float|int $vat22 = 0,

        // 1103
        #[WithCast(RoundingCaster::class)]
        public float|int $vat10 = 0,

        // 1104
        #[WithCast(RoundingCaster::class)]
        public float|int $with_vat0 = 0,

        // 1105
        #[WithCast(RoundingCaster::class)]
        public float|int $without_vat = 0,

        // 1106
        #[WithCast(RoundingCaster::class)]
        public float|int $vat122 = 0,

        // 1107
        #[WithCast(RoundingCaster::class)]
        public float|int $vat110 = 0,

        // 1199
        #[WithCast(RoundingCaster::class)]
        public float|int $vat5 = 0,

        // 1199
        #[WithCast(RoundingCaster::class)]
        public float|int $vat7 = 0,

        // 1199
        #[WithCast(RoundingCaster::class)]
        public float|int $vat105 = 0,

        // 1199
        #[WithCast(RoundingCaster::class)]
        public float|int $vat107 = 0,
    ) {
        $this->vat22 = is_float($this->vat22) ? round($this->vat22, 2, PHP_ROUND_HALF_EVEN) : $this->vat22;
        $this->vat10 = is_float($this->vat10) ? round($this->vat10, 2, PHP_ROUND_HALF_EVEN) : $this->vat10;
        $this->with_vat0 = is_float($this->with_vat0) ? round($this->with_vat0, 2, PHP_ROUND_HALF_EVEN) : $this->with_vat0;
        $this->without_vat = is_float($this->without_vat) ? round($this->without_vat, 2, PHP_ROUND_HALF_EVEN) : $this->without_vat;
        $this->vat122 = is_float($this->vat122) ? round($this->vat122, 2, PHP_ROUND_HALF_EVEN) : $this->vat122;
        $this->vat110 = is_float($this->vat110) ? round($this->vat110, 2, PHP_ROUND_HALF_EVEN) : $this->vat110;
        $this->vat5 = is_float($this->vat5) ? round($this->vat5, 2, PHP_ROUND_HALF_EVEN) : $this->vat5;
        $this->vat7 = is_float($this->vat7) ? round($this->vat7, 2, PHP_ROUND_HALF_EVEN) : $this->vat7;
        $this->vat105 = is_float($this->vat105) ? round($this->vat105, 2, PHP_ROUND_HALF_EVEN) : $this->vat105;
        $this->vat107 = is_float($this->vat107) ? round($this->vat107, 2, PHP_ROUND_HALF_EVEN) : $this->vat107;
    }
}
