<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt\Item;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class VAT extends DataTransferObject
{
    // 1199 (enum)
    public string $type;

    // 1200
    /** @var float|int|null */
    public ?float $sum;

    /**
     * VAT constructor.
     *
     * @param  string  $type
     * @param  float|int|null  $sum
     * @return self
     */
    public static function new(string $type, float $sum = null): self
    {
        return new self(compact('type', 'sum'));
    }
}
