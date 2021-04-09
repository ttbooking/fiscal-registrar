<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;
use TTBooking\FiscalRegistrar\Enums\PaymentType;

final class Payment extends DataTransferObject
{
    // 1031, 1081, 1215, 1216, 1217
    public PaymentType $type;

    // 1031, 1081, 1215, 1216, 1217
    /** @var float|int */
    public float $sum;

    /**
     * Payment constructor.
     *
     * @param  float|int  $sum
     * @param  PaymentType|null  $type
     * @return self
     */
    public static function new(float $sum, PaymentType $type = null): self
    {
        return new self(compact('sum', 'type'));
    }

    protected static function transformType($type): PaymentType
    {
        return $type ?? PaymentType::Electronic();
    }
}
