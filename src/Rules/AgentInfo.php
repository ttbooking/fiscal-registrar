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
            'paying_agent' => 'sometimes|nullable|array',
            'paying_agent.operation' => 'sometimes|nullable|string|max:24',
            'paying_agent.phones' => 'sometimes|nullable|array',
            'paying_agent.phones.*' => 'string|max:19',
            'receive_payments_operator' => 'sometimes|nullable|array',
            'receive_payments_operator.phones.*' => 'string|max:19',
            'money_transfer_operator.phones' => 'sometimes|nullable|array',
            'money_transfer_operator.phones.*' => 'string|max:19',
            'money_transfer_operator.name' => 'sometimes|nullable|string|max:64',
            'money_transfer_operator.address' => 'sometimes|nullable|string|max:256',
            'money_transfer_operator.inn' => 'sometimes|nullable|string|numeric|size:10,12',
        ] + parent::getRules();
    }
}
