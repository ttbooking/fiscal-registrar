<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO;

use DateTimeInterface;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;
use Spatie\DataTransferObject\Attributes\DefaultCast;
use Spatie\DataTransferObject\DataTransferObject as SpatieDTO;
use TTBooking\FiscalRegistrar\Casts\DataTransferObject as DTOCast;
use TTBooking\FiscalRegistrar\DTO\Casters\TimestampCaster;

#[DefaultCast(DateTimeInterface::class, TimestampCaster::class)]
abstract class DataTransferObject extends SpatieDTO implements Arrayable, Castable, JsonSerializable
{
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
}
