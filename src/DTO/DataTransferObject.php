<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use JsonSerializable;
use Spatie\DataTransferObject\DataTransferObject as SpatieDTO;
use TTBooking\FiscalRegistrar\Casts\DataTransferObject as DTOCast;

abstract class DataTransferObject extends SpatieDTO implements Arrayable, Castable, JsonSerializable
{
    public function __construct(array $parameters = [])
    {
        parent::__construct(static::transformParameters($parameters));
    }

    public function jsonSerialize()
    {
        return array_filter($this->toArray());
    }

    public function __toString()
    {
        return json_encode($this);
    }

    public static function castUsing(array $arguments)
    {
        return new DTOCast(static::class);
    }

    /**
     * @param  array<string, mixed>  $parameters
     * @return array<string, mixed>
     */
    protected static function transformParameters(array $parameters): array
    {
        // TODO: итерировать не по параметрам, а по фактическим свойствам
        foreach ($parameters as $key => &$value) {
            if (method_exists(static::class, $transform = 'transform'.Str::studly($key))) {
                $value = static::$transform($value, $parameters);
            }
        }

        return $parameters;
    }
}
