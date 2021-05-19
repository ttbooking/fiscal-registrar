<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use MyCLabs\Enum\Enum;

class EnumCast implements CastsAttributes
{
    /** @var class-string<Enum> */
    protected string $enumClass;

    /**
     * @param  class-string<Enum>  $enumClass
     * @return void
     */
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
        if (is_null($value)) {
            return null;
        }

        if (! $value instanceof Enum) {
            $value = new $this->enumClass($value);
        }

        return $value->getValue();
    }
}
