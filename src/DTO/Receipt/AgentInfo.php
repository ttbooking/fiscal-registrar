<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;
use TTBooking\FiscalRegistrar\Enums\AgentType;

final class AgentInfo extends DataTransferObject
{
    // 1057 / 1222
    public AgentType $type;

    public ?AgentInfo\PayingAgent $paying_agent;

    public ?AgentInfo\ReceivePaymentsOperator $receive_payments_operator;

    public ?AgentInfo\MoneyTransferOperator $money_transfer_operator;

    /**
     * AgentInfo constructor.
     *
     * @param  AgentType  $type
     * @param  AgentInfo\PayingAgent|null  $paying_agent
     * @param  AgentInfo\ReceivePaymentsOperator|null  $receive_payments_operator
     * @param  AgentInfo\MoneyTransferOperator|null  $money_transfer_operator
     * @return self
     */
    public static function new(
        AgentType $type,
        AgentInfo\PayingAgent $paying_agent = null,
        AgentInfo\ReceivePaymentsOperator $receive_payments_operator = null,
        AgentInfo\MoneyTransferOperator $money_transfer_operator = null
    ): self {
        return new self(compact('type', 'paying_agent', 'receive_payments_operator', 'money_transfer_operator'));
    }

    protected static function transformType($type): AgentType
    {
        return new AgentType($type);
    }
}
