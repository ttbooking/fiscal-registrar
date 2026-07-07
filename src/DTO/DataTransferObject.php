<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO;

use Spatie\LaravelData\Data;
use Stringable;

abstract class DataTransferObject extends Data implements Stringable
{
    public function __toString(): string
    {
        return $this->toJson();
    }
}
