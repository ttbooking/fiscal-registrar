<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt\Item;

final class SupplierInfo
{
    // 1171
    /** @var string[]|null */
    public ?array $phones;

    // 1225
    public ?string $name;

    // 1226
    public ?string $inn;

    /**
     * SupplierInfo constructor.
     *
     * @param  string[]|null  $phones
     * @param  string|null  $name
     * @param  string|null  $inn
     */
    public function __construct(array $phones = null, string $name = null, string $inn = null)
    {
        $this->phones = $phones;
        $this->name = $name;
        $this->inn = $inn;
    }
}
