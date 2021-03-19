<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;
use Spatie\DataTransferObject\DataTransferObject as SpatieDTO;

abstract class DataTransferObject extends SpatieDTO implements Arrayable, JsonSerializable
{
    public function jsonSerialize()
    {
        return array_filter($this->toArray());
    }

    public function __toString()
    {
        return json_encode($this);
    }
}
