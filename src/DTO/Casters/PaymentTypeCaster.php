<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Casters;

use Spatie\DataTransferObject\Caster;
use TTBooking\FiscalRegistrar\Enums\PaymentType;

class PaymentTypeCaster implements Caster
{
    public function cast(mixed $value): PaymentType
    {
        return new PaymentType($value);
    }
}
