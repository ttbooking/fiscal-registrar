<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Enums;

use Illuminate\Support\Str;

trait Translatable
{
    public function getDescription(string $variant = ''): string
    {
        return trans(implode('.', [
            'fiscal-registrar::enum',
            Str::snake(class_basename(static::class)).($variant ? '_'.$variant : $variant),
            $this->value,
        ]));
    }
}
