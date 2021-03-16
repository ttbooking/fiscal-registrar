<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

final class AdditionalUserProps
{
    // 1085
    public string $name;

    // 1086
    public string $value;

    /**
     * AdditionalUserProps constructor.
     *
     * @param  string  $name
     * @param  string  $value
     */
    public function __construct(string $name, string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }
}
