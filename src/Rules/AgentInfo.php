<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Rules;

class AgentInfo extends AggregateRule
{
    protected array $rules = [
        'type' => 'required|string|in:bank_paying_agent,bank_paying_subagent,paying_agent,paying_subagent,attorney,commission_agent,another',
        'payingAgent' => 'sometimes|nullable|array',
        'payingAgent.operation' => 'sometimes|nullable|string|max:24',
        'payingAgent.phones' => 'sometimes|nullable|array',
        'payingAgent.phones.*' => 'string|max:19',
        'receivePaymentsOperator' => 'sometimes|nullable|array',
        'receivePaymentsOperator.phones.*' => 'string|max:19',
        'moneyTransferOperator.phones' => 'sometimes|nullable|array',
        'moneyTransferOperator.phones.*' => 'string|max:19',
        'moneyTransferOperator.name' => 'sometimes|nullable|string|max:64',
        'moneyTransferOperator.address' => 'sometimes|nullable|string|max:256',
        'moneyTransferOperator.inn' => 'sometimes|nullable|string|numeric|size:10,12',
    ];
}
