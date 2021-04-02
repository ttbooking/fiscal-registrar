<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;
use TTBooking\FiscalRegistrar\Enums\AgentType;

final class AgentInfo extends DataTransferObject
{
    // 1057 / 1222
    public AgentType $type;

    public ?AgentInfo\PayingAgent $payingAgent;

    public ?AgentInfo\ReceivePaymentsOperator $receivePaymentsOperator;

    public ?AgentInfo\MoneyTransferOperator $moneyTransferOperator;

    /**
     * AgentInfo constructor.
     *
     * @param  AgentType  $type
     * @param  AgentInfo\PayingAgent|null  $payingAgent
     * @param  AgentInfo\ReceivePaymentsOperator|null  $receivePaymentsOperator
     * @param  AgentInfo\MoneyTransferOperator|null  $moneyTransferOperator
     * @return self
     */
    public static function new(
        AgentType $type,
        AgentInfo\PayingAgent $payingAgent = null,
        AgentInfo\ReceivePaymentsOperator $receivePaymentsOperator = null,
        AgentInfo\MoneyTransferOperator $moneyTransferOperator = null
    ): self {
        return new self(compact('type', 'payingAgent', 'receivePaymentsOperator', 'moneyTransferOperator'));
    }
}
