<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use Spatie\DataTransferObject\Attributes\CastWith;
use TTBooking\FiscalRegistrar\DTO\Casters\AgentTypeCaster;
use TTBooking\FiscalRegistrar\DTO\DataTransferObject;
use TTBooking\FiscalRegistrar\Enums\AgentType;

final class AgentInfo extends DataTransferObject
{
    public function __construct(

        // 1057 / 1222
        #[CastWith(AgentTypeCaster::class)]
        public AgentType $type,

        public ?AgentInfo\PayingAgent $paying_agent = null,

        public ?AgentInfo\ReceivePaymentsOperator $receive_payments_operator = null,

        public ?AgentInfo\MoneyTransferOperator $money_transfer_operator = null,

    ) {
        parent::__construct(...func_get_args());
    }
}
