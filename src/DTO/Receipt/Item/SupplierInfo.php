<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt\Item;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class SupplierInfo extends DataTransferObject
{
    public function __construct(

        // 1171
        /** @var string[]|null */
        public ?array $phones = null,

        // 1225
        public ?string $name = null,

        // 1226
        public ?string $inn = null,

    ) {
        parent::__construct(...func_get_args());
    }
}
