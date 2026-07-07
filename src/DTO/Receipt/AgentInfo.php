<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;
use TTBooking\FiscalRegistrar\Enums\AgentType;

final class AgentInfo extends DataTransferObject
{
    public function __construct(
        // 1057 / 1222
        public AgentType $type,

        public ?AgentInfo\PayingAgent $paying_agent = null,

        public ?AgentInfo\ReceivePaymentsOperator $receive_payments_operator = null,

        public ?AgentInfo\MoneyTransferOperator $money_transfer_operator = null,
    ) {}
}
