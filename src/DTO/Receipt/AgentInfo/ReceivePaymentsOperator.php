<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt\AgentInfo;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class ReceivePaymentsOperator extends DataTransferObject
{
    /**
     * @param  string[]|null  $phones
     */
    public function __construct(
        // 1074
        public ?array $phones = null,
    ) {}
}
