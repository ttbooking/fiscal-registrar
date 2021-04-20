<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt\AgentInfo;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class ReceivePaymentsOperator extends DataTransferObject
{
    public function __construct(

        // 1074
        /** @var string[]|null */
        public ?array $phones = null,

    ) {
        parent::__construct(...func_get_args());
    }
}
