<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt\Item;

use Spatie\LaravelData\Attributes\WithCast;
use TTBooking\FiscalRegistrar\DTO\Casters\RoundingCaster;
use TTBooking\FiscalRegistrar\DTO\DataTransferObject;
use TTBooking\FiscalRegistrar\Enums\VatType;

final class Vat extends DataTransferObject
{
    public function __construct(
        // 1199
        public VatType $type,

        // 1200
        #[WithCast(RoundingCaster::class)]
        public float|int|null $sum = null,
    ) {
        $this->sum = is_float($this->sum) ? round($this->sum, 2, PHP_ROUND_HALF_EVEN) : $this->sum;
    }
}
