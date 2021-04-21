<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Casters;

use Spatie\DataTransferObject\Caster;
use TTBooking\FiscalRegistrar\Enums\AgentType;

class AgentTypeCaster implements Caster
{
    public function cast(mixed $value): ?AgentType
    {
        return new AgentType($value);
    }
}
