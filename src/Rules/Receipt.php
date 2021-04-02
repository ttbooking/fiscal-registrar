<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Rules;

use Illuminate\Validation\Rule;
use TTBooking\FiscalRegistrar\Enums;

class Receipt extends AggregateRule
{
    protected function getRules(): array
    {
        return [
            'client' => 'required|array',
            'client.email' => 'sometimes|nullable|string|max:64|email',
            'client.phone' => 'sometimes|nullable|string|max:64',
            'client.name' => 'sometimes|nullable|string|max:256',
            'client.inn' => 'sometimes|nullable|string|numeric|size:10,12',

            'company' => 'sometimes|nullable|array',
            'company.email' => 'required|string|max:64|email',
            'company.sno' => ['sometimes', 'nullable', 'string', Rule::in(Enums\SNO::toArray())],
            'company.inn' => 'required|string|numeric|size:10,12',
            'company.paymentAddress' => 'required|string|max:256',

            'agentInfo' => ['sometimes', 'nullable', 'array', new AgentInfo],

            'supplierInfo' => 'required_with:agentInfo|nullable|array',
            'supplierInfo.phones' => 'sometimes|nullable|array',
            'supplierInfo.phones.*' => 'required|string|max:19',

            'items' => 'required|array|between:1,100',
            'items.*' => 'required|array',
            'items.*.name' => 'required|string|max:128',
            'items.*.price' => 'required|numeric|between:0,42949672.95',
            'items.*.quantity' => 'required|numeric|between:0,99999.999',
            'items.*.sum' => 'required|numeric|between:0,42949672.95',
            'items.*.measurementUnit' => 'sometimes|nullable|string|max:16',
            'items.*.nomenclatureCode' => 'sometimes|nullable|string|max:32',
            'items.*.paymentMethod' => ['sometimes', 'nullable', 'string', Rule::in(Enums\PaymentMethod::toArray())],
            'items.*.paymentObject' => ['sometimes', 'nullable', 'string', Rule::in(Enums\PaymentObject::toArray())],
            'items.*.vat' => 'sometimes|nullable|array',
            'items.*.vat.type' => ['required', 'string', Rule::in(Enums\VATType::toArray())],
            'items.*.vat.sum' => 'required|numeric|between:0,99999999.99',
            'items.*.agentInfo' => ['sometimes', 'nullable', 'array', new AgentInfo],
            'items.*.supplierInfo' => 'required_with:items.*.agentInfo|nullable|array',
            'items.*.supplierInfo.phones' => 'sometimes|nullable|array',
            'items.*.supplierInfo.phones.*' => 'required|string|max:19',
            'items.*.supplierInfo.name' => 'sometimes|nullable|string|max:64',
            'items.*.supplierInfo.inn' => 'sometimes|nullable|string|numeric|size:10,12',
            'items.*.userData' => 'sometimes|nullable|string|max:64',
            'items.*.excise' => 'sometimes|nullable|numeric|between:0,42949672.95',
            'items.*.countryCode' => 'sometimes|nullable|integer|between:0,999',
            'items.*.declarationNumber' => 'sometimes|nullable|string|max:32',

            'payments' => 'required|array|between:1,10',
            'payments.*' => 'required|array',
            'payments.*.type' => ['required', 'integer', Rule::in(Enums\PaymentType::toArray())],
            'payments.*.sum' => 'required|numeric|between:0,99999999.99',

            'vats' => 'sometimes|nullable|array|between:1,6',
            'vats.*' => 'required|array',
            'vats.*.type' => ['required', 'string', Rule::in(Enums\VATType::toArray())],
            'vats.*.sum' => 'required|numeric|between:0,99999999.99',

            'total' => 'required|numeric|between:0,99999999.99',

            'additionalCheckProps' => 'sometimes|nullable|string|max:16',

            'cashier' => 'sometimes|nullable|string|max:64',

            'additionalUserProps' => 'sometimes|nullable|array',
            'additionalUserProps.name' => 'required|string|max:64',
            'additionalUserProps.value' => 'required|string|max:256',
        ] + parent::getRules();
    }
}
