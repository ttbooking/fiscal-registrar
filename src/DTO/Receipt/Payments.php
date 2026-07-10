<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use Spatie\LaravelData\Attributes\WithCast;
use TTBooking\FiscalRegistrar\DTO\Casters\RoundingCaster;
use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class Payments extends DataTransferObject
{
    public function __construct(
        // 1031
        #[WithCast(RoundingCaster::class)]
        public float|int $cash = 0,

        // 1081
        #[WithCast(RoundingCaster::class)]
        public float|int $electronic = 0,

        // 1215
        #[WithCast(RoundingCaster::class)]
        public float|int $prepaid = 0,

        // 1216
        #[WithCast(RoundingCaster::class)]
        public float|int $postpaid = 0,

        // 1217
        #[WithCast(RoundingCaster::class)]
        public float|int $other = 0,
    ) {
        $this->cash = is_float($this->cash) ? round($this->cash, 2, PHP_ROUND_HALF_EVEN) : $this->cash;
        $this->electronic = is_float($this->electronic) ? round($this->electronic, 2, PHP_ROUND_HALF_EVEN) : $this->electronic;
        $this->prepaid = is_float($this->prepaid) ? round($this->prepaid, 2, PHP_ROUND_HALF_EVEN) : $this->prepaid;
        $this->postpaid = is_float($this->postpaid) ? round($this->postpaid, 2, PHP_ROUND_HALF_EVEN) : $this->postpaid;
        $this->other = is_float($this->other) ? round($this->other, 2, PHP_ROUND_HALF_EVEN) : $this->other;
    }
}
