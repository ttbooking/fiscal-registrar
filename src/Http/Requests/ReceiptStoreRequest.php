<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use TTBooking\FiscalRegistrar\Enums;

class ReceiptStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string|string[]|\Stringable|\Stringable[]>
     */
    public function rules(): array
    {
        return [
            'connection' => 'sometimes|nullable|string|max:32',
            'operation' => ['sometimes', 'nullable', 'string', Rule::in(Enums\Operation::toArray())],
            'external_id' => 'sometimes|nullable|string|max:128',

            'payload' => 'required|array',

            'payload.client' => 'required|array',
            'payload.client.email' => 'sometimes|nullable|string|max:64|email',
            'payload.client.phone' => 'sometimes|nullable|string|max:64',
            'payload.client.name' => 'sometimes|nullable|string|max:256',
            'payload.client.inn' => ['sometimes', 'nullable', 'string', 'numeric', 'regex:/^(\d{10}|\d{12})$/'],

            'payload.company' => 'required|array',
            'payload.company.name' => 'sometimes|nullable|string|max:64',
            'payload.company.email' => 'sometimes|nullable|string|max:64|email',
            'payload.company.tax_system' => ['sometimes', 'nullable', 'string', Rule::in(Enums\TaxSystem::toArray())],
            'payload.company.inn' => ['sometimes', 'nullable', 'string', 'numeric', 'regex:/^(\d{10}|\d{12})$/'],
            'payload.company.payment_address' => 'sometimes|nullable|string|max:256',
            'payload.company.payment_site' => 'sometimes|nullable|string|max:256',

            'payload.agent_info' => 'sometimes|nullable|array',
            'payload.agent_info.type' => ['required_with:payload.agent_info', 'string', Rule::in(Enums\AgentType::toArray())],
            'payload.agent_info.paying_agent' => 'sometimes|nullable|array',
            'payload.agent_info.paying_agent.operation' => 'sometimes|nullable|string|max:24',
            'payload.agent_info.paying_agent.phones' => 'sometimes|nullable|array',
            'payload.agent_info.paying_agent.phones.*' => 'string|max:19',
            'payload.agent_info.receive_payments_operator' => 'sometimes|nullable|array',
            'payload.agent_info.receive_payments_operator.phones.*' => 'string|max:19',
            'payload.agent_info.money_transfer_operator.phones' => 'sometimes|nullable|array',
            'payload.agent_info.money_transfer_operator.phones.*' => 'string|max:19',
            'payload.agent_info.money_transfer_operator.name' => 'sometimes|nullable|string|max:64',
            'payload.agent_info.money_transfer_operator.address' => 'sometimes|nullable|string|max:256',
            'payload.agent_info.money_transfer_operator.inn' => ['sometimes', 'nullable', 'string', 'numeric', 'regex:/^(\d{10}|\d{12})$/'],

            'payload.supplier_info' => 'nullable|array',
            'payload.supplier_info.phones' => 'sometimes|nullable|array',
            'payload.supplier_info.phones.*' => 'required|string|max:19',

            'payload.items' => 'required|array|between:1,100',
            'payload.items.*' => 'required|array',
            'payload.items.*.name' => 'required|string|max:128',
            'payload.items.*.price' => 'required|numeric|between:0,42949672.95',
            'payload.items.*.quantity' => 'required|numeric|between:0,99999.999',
            'payload.items.*.sum' => 'required|numeric|between:0,42949672.95',
            'payload.items.*.measurement_unit' => 'sometimes|nullable|string|max:16',
            'payload.items.*.nomenclature_code' => 'sometimes|nullable|string|max:32',
            'payload.items.*.payment_method' => ['sometimes', 'nullable', 'string', Rule::in(Enums\PaymentMethod::toArray())],
            'payload.items.*.payment_object' => ['sometimes', 'nullable', 'string', Rule::in(Enums\PaymentObject::toArray())],
            'payload.items.*.vat' => 'sometimes|nullable|array',
            'payload.items.*.vat.type' => ['required', 'string', Rule::in(Enums\VatType::toArray())],
            'payload.items.*.vat.sum' => 'sometimes|nullable|numeric|between:0,99999999.99',
            'payload.items.*.agent_info' => 'sometimes|nullable|array',
            'payload.items.*.agent_info.type' => ['required_with:payload.items.*.agent_info', 'string', Rule::in(Enums\AgentType::toArray())],
            'payload.items.*.agent_info.paying_agent' => 'sometimes|nullable|array',
            'payload.items.*.agent_info.paying_agent.operation' => 'sometimes|nullable|string|max:24',
            'payload.items.*.agent_info.paying_agent.phones' => 'sometimes|nullable|array',
            'payload.items.*.agent_info.paying_agent.phones.*' => 'string|max:19',
            'payload.items.*.agent_info.receive_payments_operator' => 'sometimes|nullable|array',
            'payload.items.*.agent_info.receive_payments_operator.phones.*' => 'string|max:19',
            'payload.items.*.agent_info.money_transfer_operator.phones' => 'sometimes|nullable|array',
            'payload.items.*.agent_info.money_transfer_operator.phones.*' => 'string|max:19',
            'payload.items.*.agent_info.money_transfer_operator.name' => 'sometimes|nullable|string|max:64',
            'payload.items.*.agent_info.money_transfer_operator.address' => 'sometimes|nullable|string|max:256',
            'payload.items.*.agent_info.money_transfer_operator.inn' => ['sometimes', 'nullable', 'string', 'numeric', 'regex:/^(\d{10}|\d{12})$/'],
            'payload.items.*.supplier_info' => 'required_with:items.*.agent_info|nullable|array',
            'payload.items.*.supplier_info.phones' => 'sometimes|nullable|array',
            'payload.items.*.supplier_info.phones.*' => 'required|string|max:19',
            'payload.items.*.supplier_info.name' => 'sometimes|nullable|string|max:64',
            'payload.items.*.supplier_info.inn' => ['sometimes', 'nullable', 'string', 'numeric', 'regex:/^(\d{10}|\d{12})$/'],
            'payload.items.*.user_data' => 'sometimes|nullable|string|max:64',
            'payload.items.*.excise' => 'sometimes|nullable|numeric|between:0,42949672.95',
            'payload.items.*.country_code' => 'sometimes|nullable|integer|between:0,999',
            'payload.items.*.declaration_number' => 'sometimes|nullable|string|max:32',

            'payload.payments' => 'sometimes|nullable|array',
            'payload.payments.cash' => 'sometimes|numeric|between:0,99999999.99',
            'payload.payments.electronic' => 'sometimes|numeric|between:0,99999999.99',
            'payload.payments.prepaid' => 'sometimes|numeric|between:0,99999999.99',
            'payload.payments.postpaid' => 'sometimes|numeric|between:0,99999999.99',
            'payload.payments.other' => 'sometimes|numeric|between:0,99999999.99',

            'payload.vats' => 'sometimes|nullable|array',
            'payload.vats.vat20' => 'sometimes|numeric|between:0,99999999.99',
            'payload.vats.vat10' => 'sometimes|numeric|between:0,99999999.99',
            'payload.vats.with_vat0' => 'sometimes|numeric|between:0,99999999.99',
            'payload.vats.without_vat' => 'sometimes|numeric|between:0,99999999.99',
            'payload.vats.vat120' => 'sometimes|numeric|between:0,99999999.99',
            'payload.vats.vat110' => 'sometimes|numeric|between:0,99999999.99',

            'payload.total' => 'required|numeric|between:0,99999999.99',
            'payload.additional_check_props' => 'sometimes|nullable|string|max:16',
            'payload.cashier' => 'sometimes|nullable|string|max:64',
            'payload.additional_user_props' => 'sometimes|nullable|array',
            'payload.additional_user_props.name' => 'sometimes|nullable|max:64',
            'payload.additional_user_props.value' => 'sometimes|nullable|max:256',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'payload.client.inn.regex' => __('fiscal-registrar::validation.inn'),
            'payload.company.inn.regex' => __('fiscal-registrar::validation.inn'),
            'payload.agent_info.money_transfer_operator.inn.regex' => __('fiscal-registrar::validation.inn'),
            'payload.items.*.agent_info.money_transfer_operator.inn.regex' => __('fiscal-registrar::validation.inn'),
            'payload.items.*.supplier_info.inn.regex' => __('fiscal-registrar::validation.inn'),
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, mixed>
     */
    public function attributes(): array
    {
        return [
            'connection' => __('fiscal-registrar::main.connection'),
            'operation' => __('fiscal-registrar::main.operation'),
            'external_id' => __('fiscal-registrar::main.external_id'),

            'payload.client.email' => __('fiscal-registrar::main.receipt.client.email'),
            'payload.client.phone' => __('fiscal-registrar::main.receipt.client.phone'),
            'payload.client.name' => __('fiscal-registrar::main.receipt.client.name'),
            'payload.client.inn' => __('fiscal-registrar::main.receipt.client.inn'),

            'payload.company.name' => __('fiscal-registrar::main.receipt.company.name'),
            'payload.company.email' => __('fiscal-registrar::main.receipt.company.email'),
            'payload.company.tax_system' => __('fiscal-registrar::main.receipt.company.tax_system'),
            'payload.company.inn' => __('fiscal-registrar::main.receipt.company.inn'),
            'payload.company.payment_address' => __('fiscal-registrar::main.receipt.company.payment_address'),
            'payload.company.payment_site' => __('fiscal-registrar::main.receipt.company.payment_site'),

            'payload.agent_info.type' => __('fiscal-registrar::main.receipt.agent_info.type'),
            'payload.agent_info.paying_agent.operation' => __('fiscal-registrar::main.receipt.agent_info.paying_agent.operation'),
            'payload.agent_info.paying_agent.phones.*' => __('fiscal-registrar::main.receipt.agent_info.paying_agent.phones'),
            'payload.agent_info.receive_payments_operator.phones.*' => __('fiscal-registrar::main.receipt.agent_info.receive_payments_operator.phones'),
            'payload.agent_info.money_transfer_operator.phones.*' => __('fiscal-registrar::main.receipt.agent_info.money_transfer_operator.phones'),
            'payload.agent_info.money_transfer_operator.name' => __('fiscal-registrar::main.receipt.agent_info.money_transfer_operator.name'),
            'payload.agent_info.money_transfer_operator.address' => __('fiscal-registrar::main.receipt.agent_info.money_transfer_operator.address'),
            'payload.agent_info.money_transfer_operator.inn' => __('fiscal-registrar::main.receipt.agent_info.money_transfer_operator.inn'),

            'payload.supplier_info.phones.*' => __('fiscal-registrar::main.receipt.supplier_info.phones'),

            'payload.items.*.name' => __('fiscal-registrar::main.receipt.items.name'),
            'payload.items.*.price' => __('fiscal-registrar::main.receipt.items.price'),
            'payload.items.*.quantity' => __('fiscal-registrar::main.receipt.items.quantity'),
            'payload.items.*.sum' => __('fiscal-registrar::main.receipt.items.sum'),
            'payload.items.*.measurement_unit' => __('fiscal-registrar::main.receipt.items.measurement_unit'),
            'payload.items.*.nomenclature_code' => __('fiscal-registrar::main.receipt.items.nomenclature_code'),
            'payload.items.*.payment_method' => __('fiscal-registrar::main.receipt.items.payment_method'),
            'payload.items.*.payment_object' => __('fiscal-registrar::main.receipt.items.payment_object'),
            'payload.items.*.vat.type' => __('fiscal-registrar::main.receipt.items.vat.type'),
            'payload.items.*.vat.sum' => __('fiscal-registrar::main.receipt.items.vat.sum'),
            'payload.items.*.agent_info.type' => __('fiscal-registrar::main.receipt.items.agent_info.type'),
            'payload.items.*.agent_info.paying_agent.operation' => __('fiscal-registrar::main.receipt.agent_info.paying_agent.operation'),
            'payload.items.*.agent_info.paying_agent.phones.*' => __('fiscal-registrar::main.receipt.agent_info.paying_agent.phones'),
            'payload.items.*.agent_info.receive_payments_operator.phones.*' => __('fiscal-registrar::main.receipt.agent_info.receive_payments_operator.phones'),
            'payload.items.*.agent_info.money_transfer_operator.phones.*' => __('fiscal-registrar::main.receipt.agent_info.money_transfer_operator.phones'),
            'payload.items.*.agent_info.money_transfer_operator.name' => __('fiscal-registrar::main.receipt.agent_info.money_transfer_operator.name'),
            'payload.items.*.agent_info.money_transfer_operator.address' => __('fiscal-registrar::main.receipt.agent_info.money_transfer_operator.address'),
            'payload.items.*.agent_info.money_transfer_operator.inn' => __('fiscal-registrar::main.receipt.agent_info.money_transfer_operator.inn'),
            'payload.items.*.supplier_info.phones.*' => __('fiscal-registrar::main.receipt.items.supplier_info.phones'),
            'payload.items.*.supplier_info.name' => __('fiscal-registrar::main.receipt.items.supplier_info.name'),
            'payload.items.*.supplier_info.inn' => __('fiscal-registrar::main.receipt.items.supplier_info.inn'),
            'payload.items.*.user_data' => __('fiscal-registrar::main.receipt.items.user_data'),
            'payload.items.*.excise' => __('fiscal-registrar::main.receipt.items.excise'),
            'payload.items.*.country_code' => __('fiscal-registrar::main.receipt.items.country_code'),
            'payload.items.*.declaration_number' => __('fiscal-registrar::main.receipt.items.declaration_number'),

            'payload.payments.cash' => __('fiscal-registrar::main.receipt.payments.cash'),
            'payload.payments.electronic' => __('fiscal-registrar::main.receipt.payments.electronic'),
            'payload.payments.prepaid' => __('fiscal-registrar::main.receipt.payments.prepaid'),
            'payload.payments.postpaid' => __('fiscal-registrar::main.receipt.payments.postpaid'),
            'payload.payments.other' => __('fiscal-registrar::main.receipt.payments.other'),

            'payload.vats.vat20' => __('fiscal-registrar::main.receipt.vats.vat20'),
            'payload.vats.vat10' => __('fiscal-registrar::main.receipt.vats.vat10'),
            'payload.vats.with_vat0' => __('fiscal-registrar::main.receipt.vats.with_vat0'),
            'payload.vats.without_vat' => __('fiscal-registrar::main.receipt.vats.without_vat'),
            'payload.vats.vat120' => __('fiscal-registrar::main.receipt.vats.vat120'),
            'payload.vats.vat110' => __('fiscal-registrar::main.receipt.vats.vat110'),

            'payload.total' => __('fiscal-registrar::main.receipt.total'),
            'payload.additional_check_props' => __('fiscal-registrar::main.receipt.additional_check_props'),
            'payload.cashier' => __('fiscal-registrar::main.receipt.cashier'),
            'payload.additional_user_props.name' => __('fiscal-registrar::main.receipt.additional_user_props.name'),
            'payload.additional_user_props.value' => __('fiscal-registrar::main.receipt.additional_user_props.value'),
        ];
    }
}
