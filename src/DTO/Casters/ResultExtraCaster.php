<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Casters;

use Spatie\DataTransferObject\Caster;

class ResultExtraCaster implements Caster
{
    public function cast(mixed $value): ?object
    {
        return isset($value) ? (object) $value : null;
    }
}
