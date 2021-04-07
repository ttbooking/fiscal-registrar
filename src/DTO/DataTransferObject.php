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
        parent::__construct($this->transformParameters($parameters));
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
    protected function transformParameters(array $parameters): array
    {
        foreach (array_keys($this->getFieldValidators()) as $field) {
            if (method_exists(static::class, $transform = 'transform'.Str::studly($field))) {
                $parameters[$field] = static::$transform($parameters[$field] ?? null, $parameters);
            }
        }

        return $parameters;
    }
}
