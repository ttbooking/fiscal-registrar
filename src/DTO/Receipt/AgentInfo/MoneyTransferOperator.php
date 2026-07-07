<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt\AgentInfo;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class MoneyTransferOperator extends DataTransferObject
{
    /**
     * @param  string[]|null  $phones
     */
    public function __construct(
        // 1075
        public ?array $phones = null,

        // 1026
        public ?string $name = null,

        // 1005
        public ?string $address = null,

        // 1016
        public ?string $inn = null,
    ) {}
}
