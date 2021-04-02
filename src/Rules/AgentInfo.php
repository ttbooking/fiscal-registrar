<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Rules;

use Illuminate\Validation\Rule;
use TTBooking\FiscalRegistrar\Enums\AgentType;

class AgentInfo extends AggregateRule
{
    protected function getRules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in(AgentType::toArray())],
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
        ] + parent::getRules();
    }
}
