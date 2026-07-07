<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Casters;

use Carbon\Carbon;
use DateTimeInterface;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class TimestampCaster implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): DateTimeInterface
    {
        return Carbon::parse($value)->settings(['toJsonFormat' => 'c']);
    }
}
