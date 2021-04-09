<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;
use TTBooking\FiscalRegistrar\Enums\VATType;

final class VAT extends DataTransferObject
{
    // 1105, 1104, 1103, 1102, 1107, 1106
    public VATType $type;

    // 1105, 1104, 1103, 1102, 1107, 1106
    /** @var float|int */
    public float $sum;

    /**
     * VAT constructor.
     *
     * @param  VATType  $type
     * @param  float|int  $sum
     * @return self
     */
    public static function new(VATType $type, float $sum): self
    {
        return new self(compact('type', 'sum'));
    }

    protected static function transformType($type): VATType
    {
        return new VATType($type);
    }
}
