<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Casters;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class ResultExtraCaster implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): ?object
    {
        return isset($value) ? (object) $value : null;
    }
}
