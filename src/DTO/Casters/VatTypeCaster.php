<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Casters;

use Spatie\DataTransferObject\Caster;
use TTBooking\FiscalRegistrar\Enums\VatType;

class VatTypeCaster implements Caster
{
    public function cast(mixed $value): VatType
    {
        return new VatType($value);
    }
}
