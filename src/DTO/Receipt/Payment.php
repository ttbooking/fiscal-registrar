<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use Spatie\DataTransferObject\Attributes\CastWith;
use TTBooking\FiscalRegistrar\DTO\Casters\PaymentTypeCaster;
use TTBooking\FiscalRegistrar\DTO\DataTransferObject;
use TTBooking\FiscalRegistrar\Enums\PaymentType;

final class Payment extends DataTransferObject
{
    // 1031, 1081, 1215, 1216, 1217
    public float|int $sum;

    // 1031, 1081, 1215, 1216, 1217
    #[CastWith(PaymentTypeCaster::class)]
    public PaymentType $type;

    protected static function transformType($type): PaymentType
    {
        return $type ?? PaymentType::Electronic();
    }
}
