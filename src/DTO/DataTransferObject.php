<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO;

use DateTimeInterface;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Str;
use JsonSerializable;
use Spatie\DataTransferObject\Attributes\DefaultCast;
use Spatie\DataTransferObject\DataTransferObject as SpatieDTO;
use Spatie\DataTransferObject\Reflection\DataTransferObjectClass;
use Stringable;
use TTBooking\FiscalRegistrar\Casts\DTOCast;
use TTBooking\FiscalRegistrar\DTO\Casters\TimestampCaster;

/**
 * @implements Arrayable<string, mixed>
 */
#[DefaultCast(DateTimeInterface::class, TimestampCaster::class)]
abstract class DataTransferObject extends SpatieDTO implements Arrayable, Castable, Jsonable, JsonSerializable, Stringable
{
    public function __construct(mixed ...$args)
    {
        if (is_array($args[0] ?? null)) {
            $args = $args[0];
        }

        parent::__construct(...$this->transformParameters($args));
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * @param  array<mixed>  $arguments
     */
    public static function castUsing(array $arguments): DTOCast
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
