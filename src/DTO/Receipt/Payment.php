<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class Payment extends DataTransferObject
{
    // 1031, 1081, 1215, 1216, 1217 (enum)
    public int $type;

    // 1031, 1081, 1215, 1216, 1217
    /** @var float|int */
    public float $sum;

    /**
     * Payment constructor.
     *
     * @param  int  $type
     * @param  float  $sum
     * @return self
     */
    public static function new(int $type, float $sum): self
    {
        return new self(compact('type', 'sum'));
    }
}
