<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt\AgentInfo;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class PayingAgent extends DataTransferObject
{
    public function __construct(

        // 1044
        public ?string $operation = null,

        // 1073
        /** @var string[]|null */
        public ?array $phones = null,

    ) {
        parent::__construct(...func_get_args());
    }
}
