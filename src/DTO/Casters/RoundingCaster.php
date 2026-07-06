<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Casters;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class RoundingCaster implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): ?float
    {
        return isset($value) ? round((float) $value, 2, PHP_ROUND_HALF_EVEN) : null;
    }
}
