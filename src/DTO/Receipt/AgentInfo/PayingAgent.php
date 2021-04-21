<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt\AgentInfo;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class PayingAgent extends DataTransferObject
{
    // 1044
    public ?string $operation = null;

    // 1073
    /** @var string[]|null */
    public ?array $phones = null;
}
