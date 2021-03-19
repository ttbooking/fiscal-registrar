<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class VAT extends DataTransferObject
{
    // 1105, 1104, 1103, 1102, 1107, 1106 (enum)
    public string $type;

    // 1105, 1104, 1103, 1102, 1107, 1106
    public float $sum;

    /**
     * VAT constructor.
     *
     * @param  string  $type
     * @param  float  $sum
     * @return self
     */
    public static function new(string $type, float $sum): self
    {
        return new self(compact('type', 'sum'));
    }
}
