<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt\Item;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class SupplierInfo extends DataTransferObject
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
     * @return self
     */
    public static function new(array $phones = null, string $name = null, string $inn = null): self
    {
        return new self(compact('phones', 'name', 'inn'));
    }
}
