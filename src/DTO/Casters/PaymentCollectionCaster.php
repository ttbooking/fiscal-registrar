<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Casters;

use Spatie\DataTransferObject\Caster;
use TTBooking\FiscalRegistrar\DTO\Receipt\Payment;
use TTBooking\FiscalRegistrar\DTO\Receipt\PaymentCollection;

class PaymentCollectionCaster implements Caster
{
    public function cast(mixed $value): ?PaymentCollection
    {
        return isset($value)
            ? new PaymentCollection(array_map(fn (array $data) => new Payment(...$data), $value))
            : null;
    }
}
