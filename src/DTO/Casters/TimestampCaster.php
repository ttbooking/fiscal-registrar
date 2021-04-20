<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Casters;

use Carbon\Carbon;
use DateTimeInterface;
use Spatie\DataTransferObject\Caster;

class TimestampCaster implements Caster
{
    public function cast(mixed $value): DateTimeInterface
    {
        return Carbon::parse($value)->settings(['toJsonFormat' => 'c']);
    }
}
