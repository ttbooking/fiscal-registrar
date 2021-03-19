<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

use TTBooking\FiscalRegistrar\DTO\DataTransferObject;

final class AgentInfo extends DataTransferObject
{
    // 1057 / 1222
    public string $type;

    public ?AgentInfo\PayingAgent $payingAgent;

    public ?AgentInfo\ReceivePaymentsOperator $receivePaymentsOperator;

    public ?AgentInfo\MoneyTransferOperator $moneyTransferOperator;

    /**
     * AgentInfo constructor.
     *
     * @param  string  $type
     * @param  AgentInfo\PayingAgent|null  $payingAgent
     * @param  AgentInfo\ReceivePaymentsOperator|null  $receivePaymentsOperator
     * @param  AgentInfo\MoneyTransferOperator|null  $moneyTransferOperator
     * @return self
     */
    public static function new(
        string $type,
        AgentInfo\PayingAgent $payingAgent = null,
        AgentInfo\ReceivePaymentsOperator $receivePaymentsOperator = null,
        AgentInfo\MoneyTransferOperator $moneyTransferOperator = null
    ): self {
        return new self(compact('type', 'payingAgent', 'receivePaymentsOperator', 'moneyTransferOperator'));
    }
}
