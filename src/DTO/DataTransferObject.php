<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO;

use DateTimeInterface;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use JsonSerializable;
use Spatie\DataTransferObject\Attributes\DefaultCast;
use Spatie\DataTransferObject\DataTransferObject as SpatieDTO;
use Spatie\DataTransferObject\Reflection\DataTransferObjectClass;
use TTBooking\FiscalRegistrar\Casts\DataTransferObject as DTOCast;
use TTBooking\FiscalRegistrar\DTO\Casters\TimestampCaster;

#[DefaultCast(DateTimeInterface::class, TimestampCaster::class)]
abstract class DataTransferObject extends SpatieDTO implements Arrayable, Castable, JsonSerializable
{
    public function __construct(...$args)
    {
        if (is_array($args[0] ?? null)) {
            $args = $args[0];
        }

        parent::__construct(...$this->transformParameters($args));
    }

    public function jsonSerialize()
    {
        return $this->toArray();
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
     * @param  array<string, mixed>  $args
     * @return array<string, mixed>
     */
    protected function transformParameters(array $args): array
    {
        $class = new DataTransferObjectClass($this);

        foreach ($class->getProperties() as $property) {
            if (method_exists(static::class, $transform = 'transform'.Str::studly($property->name))) {
                $args[$property->name] = static::$transform($args[$property->name] ?? null, $args);
            }
        }

        return $args;
    }
}
