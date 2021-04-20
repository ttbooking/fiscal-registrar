<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt\AgentInfo;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class MoneyTransferOperator extends DataTransferObject
{
    public function __construct(

        // 1075
        /** @var string[]|null */
        public ?array $phones = null,

        // 1026
        public ?string $name = null,

        // 1005
        public ?string $address = null,

        // 1016
        public ?string $inn = null,

    ) {
        parent::__construct(...func_get_args());
    }
}
