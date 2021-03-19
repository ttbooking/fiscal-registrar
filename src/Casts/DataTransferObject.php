<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class DataTransferObject implements CastsAttributes
{
    protected string $dtoClass;

    public function __construct(string $dtoClass)
    {
        $this->dtoClass = $dtoClass;
    }

    public function get($model, string $key, $value, array $attributes)
    {
        return new ($this->dtoClass)(json_decode($value, true));
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return $value;
    }
}
