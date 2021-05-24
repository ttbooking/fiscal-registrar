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

            'company' => 'required|array',
            'company.email' => 'sometimes|nullable|string|max:64|email',
            'company.tax_system' => ['sometimes', 'nullable', 'string', Rule::in(Enums\TaxSystem::toArray())],
            'company.inn' => 'sometimes|nullable|string|numeric',
            'company.payment_address' => 'sometimes|nullable|string|max:256',

            'agent_info' => ['sometimes', 'nullable', 'array', new AgentInfo],

            'supplier_info' => 'nullable|array',
            'supplier_info.phones' => 'sometimes|nullable|array',
            'supplier_info.phones.*' => 'required|string|max:19',

            'items' => 'required|array|between:1,100',
            'items.*' => 'required|array',
            'items.*.name' => 'required|string|max:128',
            'items.*.price' => 'required|numeric|between:0,42949672.95',
            'items.*.quantity' => 'required|numeric|between:0,99999.999',
            'items.*.sum' => 'required|numeric|between:0,42949672.95',
            'items.*.measurement_unit' => 'sometimes|nullable|string|max:16',
            'items.*.nomenclature_code' => 'sometimes|nullable|string|max:32',
            'items.*.payment_method' => ['sometimes', 'nullable', 'string', Rule::in(Enums\PaymentMethod::toArray())],
            'items.*.payment_object' => ['sometimes', 'nullable', 'string', Rule::in(Enums\PaymentObject::toArray())],
            'items.*.vat' => 'sometimes|nullable|array',
            'items.*.vat.type' => ['required', 'string', Rule::in(Enums\VatType::toArray())],
            'items.*.vat.sum' => 'sometimes|nullable|numeric|between:0,99999999.99',
            'items.*.agent_info' => ['sometimes', 'nullable', 'array', new AgentInfo],
            'items.*.supplier_info' => 'required_with:items.*.agent_info|nullable|array',
            'items.*.supplier_info.phones' => 'sometimes|nullable|array',
            'items.*.supplier_info.phones.*' => 'required|string|max:19',
            'items.*.supplier_info.name' => 'sometimes|nullable|string|max:64',
            'items.*.supplier_info.inn' => 'sometimes|nullable|string|numeric',
            'items.*.user_data' => 'sometimes|nullable|string|max:64',
            'items.*.excise' => 'sometimes|nullable|numeric|between:0,42949672.95',
            'items.*.country_code' => 'sometimes|nullable|integer|between:0,999',
            'items.*.declaration_number' => 'sometimes|nullable|string|max:32',

            'payments' => 'sometimes|nullable|array',
            'payments.cash' => 'sometimes|numeric|between:0,99999999.99',
            'payments.electronic' => 'sometimes|numeric|between:0,99999999.99',
            'payments.prepaid' => 'sometimes|numeric|between:0,99999999.99',
            'payments.postpaid' => 'sometimes|numeric|between:0,99999999.99',
            'payments.other' => 'sometimes|numeric|between:0,99999999.99',

            'vats' => 'sometimes|nullable|array',
            'vats.vat20' => 'sometimes|numeric|between:0,99999999.99',
            'vats.vat10' => 'sometimes|numeric|between:0,99999999.99',
            'vats.with_vat0' => 'sometimes|numeric|between:0,99999999.99',
            'vats.without_vat' => 'sometimes|numeric|between:0,99999999.99',
            'vats.vat120' => 'sometimes|numeric|between:0,99999999.99',
            'vats.vat110' => 'sometimes|numeric|between:0,99999999.99',

            'total' => 'required|numeric|between:0,99999999.99',

            'additional_check_props' => 'sometimes|nullable|string|max:16',

            'cashier' => 'sometimes|nullable|string|max:64',

            'additional_user_props' => 'sometimes|nullable|array',
            'additional_user_props.name' => 'sometimes|nullable|max:64',
            'additional_user_props.value' => 'sometimes|nullable|max:256',
        ] + parent::getRules();
    }
}
