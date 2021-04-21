<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class SupplierInfo extends DataTransferObject
{
    // 1171
    /** @var string[]|null */
    public ?array $phones = null;
}
