<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Casters;

use Spatie\DataTransferObject\Caster;
use TTBooking\FiscalRegistrar\Enums\PaymentMethod;

class PaymentMethodCaster implements Caster
{
    public function cast(mixed $value): PaymentMethod
    {
        return new PaymentMethod($value);
    }
}
