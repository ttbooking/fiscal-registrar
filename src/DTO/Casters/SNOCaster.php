<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Casters;

use Spatie\DataTransferObject\Caster;
use TTBooking\FiscalRegistrar\Enums\SNO;

class SNOCaster implements Caster
{
    public function cast(mixed $value): ?SNO
    {
        return isset($value) ? new SNO($value) : null;
    }
}
