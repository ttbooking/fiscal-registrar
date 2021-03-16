<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\DTO\Receipt;

final class AgentInfo
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
     */
    public function __construct(
        string $type,
        AgentInfo\PayingAgent $payingAgent = null,
        AgentInfo\ReceivePaymentsOperator $receivePaymentsOperator = null,
        AgentInfo\MoneyTransferOperator $moneyTransferOperator = null
    ) {
        $this->type = $type;
        $this->payingAgent = $payingAgent;
        $this->receivePaymentsOperator = $receivePaymentsOperator;
        $this->moneyTransferOperator = $moneyTransferOperator;
    }
}
