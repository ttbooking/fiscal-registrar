<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Enum implements CastsAttributes
{
    protected string $enumClass;

    public function __construct(string $enumClass)
    {
        $this->enumClass = $enumClass;
    }

    public function get($model, string $key, $value, array $attributes)
    {
        return isset($value) ? new $this->enumClass($value) : null;
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return $value->getValue();
    }
}
