<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Casters;

use Spatie\DataTransferObject\Caster;
use TTBooking\FiscalRegistrar\Enums\VATType;

class VATTypeCaster implements Caster
{
    public function cast(mixed $value): VATType
    {
        return isset($value) ? new VATType($value) : VATType::None();
    }
}
