<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Casters;

use Spatie\DataTransferObject\Caster;
use TTBooking\FiscalRegistrar\Enums\PaymentObject;

class PaymentObjectCaster implements Caster
{
    public function cast(mixed $value): PaymentObject
    {
        return isset($value) ? new PaymentObject($value) : PaymentObject::Commodity();
    }
}
