<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class SupplierInfo extends DataTransferObject
{
    public function __construct(

        // 1171
        /** @var string[]|null */
        public ?array $phones = null,

    ) {
        parent::__construct(...func_get_args());
    }
}
