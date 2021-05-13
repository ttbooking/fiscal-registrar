<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Casters;

use Spatie\DataTransferObject\Caster;

class RoundingCaster implements Caster
{
    public function cast(mixed $value): ?float
    {
        return isset($value) ? round((float) $value, 2, PHP_ROUND_HALF_EVEN) : null;
    }
}
