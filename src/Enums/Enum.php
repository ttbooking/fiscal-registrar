<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Enums;

use Illuminate\Contracts\Database\Eloquent\Castable;
use MyCLabs\Enum\Enum as BaseEnum;
use TTBooking\FiscalRegistrar\Casts\Enum as EnumCast;

abstract class Enum extends BaseEnum implements Castable
{
    public static function castUsing(array $arguments)
    {
        return new EnumCast(static::class);
    }
}
