<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt\Item;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;
use TTBooking\FiscalRegistrar\Enums\VATType;

final class VAT extends DataTransferObject
{
    // 1199
    public VATType $type;

    // 1200
    /** @var float|int|null */
    public ?float $sum;

    /**
     * VAT constructor.
     *
     * @param  VATType  $type
     * @param  float|int|null  $sum
     * @return self
     */
    public static function new(VATType $type, float $sum = null): self
    {
        return new self(compact('type', 'sum'));
    }
}
