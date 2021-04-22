<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Casters;

use Spatie\DataTransferObject\Caster;
use TTBooking\FiscalRegistrar\Enums\TaxSystem;

class TaxSystemCaster implements Caster
{
    public function cast(mixed $value): ?TaxSystem
    {
        return new TaxSystem($value);
    }
}
