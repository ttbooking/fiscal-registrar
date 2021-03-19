<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class SupplierInfo extends DataTransferObject
{
    // 1171
    /** @var string[]|null */
    public ?array $phones;

    /**
     * SupplierInfo constructor.
     *
     * @param  string[]|null  $phones
     * @return self
     */
    public static function new(array $phones = null): self
    {
        return new self(compact('phones'));
    }
}
