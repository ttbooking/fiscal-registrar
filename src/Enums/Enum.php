<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Enums;

use MyCLabs\Enum\Enum as BaseEnum;
use TTBooking\FiscalRegistrar\Casts\Enum as EnumCast;

abstract class Enum extends BaseEnum
{
    public static function castUsing(array $arguments)
    {
        return new EnumCast(static::class);
    }
}
