<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class SupplierInfo extends DataTransferObject
{
    /**
     * @param  string[]|null  $phones
     */
    public function __construct(
        // 1171
        public ?array $phones = null,
    ) {}
}
