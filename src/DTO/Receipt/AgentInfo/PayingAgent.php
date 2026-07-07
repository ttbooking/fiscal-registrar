<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt\AgentInfo;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class PayingAgent extends DataTransferObject
{
    /**
     * @param  string[]|null  $phones
     */
    public function __construct(
        // 1044
        public ?string $operation = null,

        // 1073
        public ?array $phones = null,
    ) {}
}
