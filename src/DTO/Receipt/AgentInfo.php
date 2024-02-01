<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Casters\EnumCaster;
use TTBooking\FiscalRegistrar\DTO\DataTransferObject;
use TTBooking\FiscalRegistrar\Enums\AgentType;

final class AgentInfo extends DataTransferObject
{
    // 1057 / 1222
    #[CastWith(EnumCaster::class, AgentType::class)]
    public AgentType $type;

    public ?AgentInfo\PayingAgent $paying_agent = null;

    public ?AgentInfo\ReceivePaymentsOperator $receive_payments_operator = null;

    public ?AgentInfo\MoneyTransferOperator $money_transfer_operator = null;
}
