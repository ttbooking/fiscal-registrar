<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Enums;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use MyCLabs\Enum\Enum as BaseEnum;
use Spatie\DataTransferObject\Attributes\CastWith;
use TTBooking\FiscalRegistrar\Casts\EnumCast;
use TTBooking\FiscalRegistrar\DTO\Casters\EnumCaster;

#[CastWith(EnumCaster::class)]
abstract class Enum extends BaseEnum implements Castable
{
    public function getDescription(string $variant = ''): string
    {
        return Lang::get($this->getDescriptionKey($variant));
    }

    protected function getDescriptionKey(string $variant = ''): string
    {
        return implode('.', [
            'fiscal-registrar::enum',
            Str::snake(class_basename(static::class)).($variant ? '_'.$variant : $variant),
            $this->getValue(),
        ]);
    }

    public static function castUsing(array $arguments)
    {
        return new EnumCast(static::class);
    }
}
