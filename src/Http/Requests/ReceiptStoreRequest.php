<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use TTBooking\FiscalRegistrar\Enums;

class ReceiptStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'connection' => 'sometimes|nullable|string|max:32',
            'operation' => ['sometimes', 'nullable', 'string', Rule::in(Enums\Operation::toArray())],
            'external_id' => 'sometimes|nullable|string|max:128',

            'data' => 'required|array',

            'data.client' => 'required|array',
            'data.client.email' => 'sometimes|nullable|string|max:64|email',
            'data.client.phone' => 'sometimes|nullable|string|max:64',
            'data.client.name' => 'sometimes|nullable|string|max:256',
            'data.client.inn' => ['sometimes', 'nullable', 'string', 'numeric', 'regex:/^(\d{10}|\d{12})$/'],

            'data.company' => 'required|array',
            'data.company.name' => 'sometimes|nullable|string|max:64',
            'data.company.email' => 'sometimes|nullable|string|max:64|email',
            'data.company.tax_system' => ['sometimes', 'nullable', 'string', Rule::in(Enums\TaxSystem::toArray())],
            'data.company.inn' => ['sometimes', 'nullable', 'string', 'numeric', 'regex:/^(\d{10}|\d{12})$/'],
            'data.company.payment_address' => 'sometimes|nullable|string|max:256',
            'data.company.payment_site' => 'sometimes|nullable|string|max:256',

            'data.agent_info' => 'sometimes|nullable|array',
            'data.agent_info.type' => ['required_with:data.agent_info', 'string', Rule::in(Enums\AgentType::toArray())],
            'data.agent_info.paying_agent' => 'sometimes|nullable|array',
            'data.agent_info.paying_agent.operation' => 'sometimes|nullable|string|max:24',
            'data.agent_info.paying_agent.phones' => 'sometimes|nullable|array',
            'data.agent_info.paying_agent.phones.*' => 'string|max:19',
            'data.agent_info.receive_payments_operator' => 'sometimes|nullable|array',
            'data.agent_info.receive_payments_operator.phones.*' => 'string|max:19',
            'data.agent_info.money_transfer_operator.phones' => 'sometimes|nullable|array',
            'data.agent_info.money_transfer_operator.phones.*' => 'string|max:19',
            'data.agent_info.money_transfer_operator.name' => 'sometimes|nullable|string|max:64',
            'data.agent_info.money_transfer_operator.address' => 'sometimes|nullable|string|max:256',
            'data.agent_info.money_transfer_operator.inn' => ['sometimes', 'nullable', 'string', 'numeric', 'regex:/^(\d{10}|\d{12})$/'],

            'data.supplier_info' => 'nullable|array',
            'data.supplier_info.phones' => 'sometimes|nullable|array',
            'data.supplier_info.phones.*' => 'required|string|max:19',

            'data.items' => 'required|array|between:1,100',
            'data.items.*' => 'required|array',
            'data.items.*.name' => 'required|string|max:128',
            'data.items.*.price' => 'required|numeric|between:0,42949672.95',
            'data.items.*.quantity' => 'required|numeric|between:0,99999.999',
            'data.items.*.sum' => 'required|numeric|between:0,42949672.95',
            'data.items.*.measurement_unit' => 'sometimes|nullable|string|max:16',
            'data.items.*.nomenclature_code' => 'sometimes|nullable|string|max:32',
            'data.items.*.payment_method' => ['sometimes', 'nullable', 'string', Rule::in(Enums\PaymentMethod::toArray())],
            'data.items.*.payment_object' => ['sometimes', 'nullable', 'string', Rule::in(Enums\PaymentObject::toArray())],
            'data.items.*.vat' => 'sometimes|nullable|array',
            'data.items.*.vat.type' => ['required', 'string', Rule::in(Enums\VatType::toArray())],
            'data.items.*.vat.sum' => 'sometimes|nullable|numeric|between:0,99999999.99',
            'data.items.*.agent_info' => 'sometimes|nullable|array',
            'data.items.*.agent_info.type' => ['required_with:data.items.*.agent_info', 'string', Rule::in(Enums\AgentType::toArray())],
            'data.items.*.agent_info.paying_agent' => 'sometimes|nullable|array',
            'data.items.*.agent_info.paying_agent.operation' => 'sometimes|nullable|string|max:24',
            'data.items.*.agent_info.paying_agent.phones' => 'sometimes|nullable|array',
            'data.items.*.agent_info.paying_agent.phones.*' => 'string|max:19',
            'data.items.*.agent_info.receive_payments_operator' => 'sometimes|nullable|array',
            'data.items.*.agent_info.receive_payments_operator.phones.*' => 'string|max:19',
            'data.items.*.agent_info.money_transfer_operator.phones' => 'sometimes|nullable|array',
            'data.items.*.agent_info.money_transfer_operator.phones.*' => 'string|max:19',
            'data.items.*.agent_info.money_transfer_operator.name' => 'sometimes|nullable|string|max:64',
            'data.items.*.agent_info.money_transfer_operator.address' => 'sometimes|nullable|string|max:256',
            'data.items.*.agent_info.money_transfer_operator.inn' => ['sometimes', 'nullable', 'string', 'numeric', 'regex:/^(\d{10}|\d{12})$/'],
            'data.items.*.supplier_info' => 'required_with:items.*.agent_info|nullable|array',
            'data.items.*.supplier_info.phones' => 'sometimes|nullable|array',
            'data.items.*.supplier_info.phones.*' => 'required|string|max:19',
            'data.items.*.supplier_info.name' => 'sometimes|nullable|string|max:64',
            'data.items.*.supplier_info.inn' => ['sometimes', 'nullable', 'string', 'numeric', 'regex:/^(\d{10}|\d{12})$/'],
            'data.items.*.user_data' => 'sometimes|nullable|string|max:64',
            'data.items.*.excise' => 'sometimes|nullable|numeric|between:0,42949672.95',
            'data.items.*.country_code' => 'sometimes|nullable|integer|between:0,999',
            'data.items.*.declaration_number' => 'sometimes|nullable|string|max:32',

            'data.payments' => 'sometimes|nullable|array',
            'data.payments.cash' => 'sometimes|numeric|between:0,99999999.99',
            'data.payments.electronic' => 'sometimes|numeric|between:0,99999999.99',
            'data.payments.prepaid' => 'sometimes|numeric|between:0,99999999.99',
            'data.payments.postpaid' => 'sometimes|numeric|between:0,99999999.99',
            'data.payments.other' => 'sometimes|numeric|between:0,99999999.99',

            'data.vats' => 'sometimes|nullable|array',
            'data.vats.vat20' => 'sometimes|numeric|between:0,99999999.99',
            'data.vats.vat10' => 'sometimes|numeric|between:0,99999999.99',
            'data.vats.with_vat0' => 'sometimes|numeric|between:0,99999999.99',
            'data.vats.without_vat' => 'sometimes|numeric|between:0,99999999.99',
            'data.vats.vat120' => 'sometimes|numeric|between:0,99999999.99',
            'data.vats.vat110' => 'sometimes|numeric|between:0,99999999.99',

            'data.total' => 'required|numeric|between:0,99999999.99',
            'data.additional_check_props' => 'sometimes|nullable|string|max:16',
            'data.cashier' => 'sometimes|nullable|string|max:64',
            'data.additional_user_props' => 'sometimes|nullable|array',
            'data.additional_user_props.name' => 'sometimes|nullable|max:64',
            'data.additional_user_props.value' => 'sometimes|nullable|max:256',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'data.client.inn.regex' => __('fiscal-registrar::validation.inn'),
            'data.company.inn.regex' => __('fiscal-registrar::validation.inn'),
            'data.agent_info.money_transfer_operator.inn.regex' => __('fiscal-registrar::validation.inn'),
            'data.items.*.agent_info.money_transfer_operator.inn.regex' => __('fiscal-registrar::validation.inn'),
            'data.items.*.supplier_info.inn.regex' => __('fiscal-registrar::validation.inn'),
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'connection' => __('fiscal-registrar::main.connection'),
            'operation' => __('fiscal-registrar::main.operation'),
            'external_id' => __('fiscal-registrar::main.external_id'),

            'data.client.email' => __('fiscal-registrar::main.receipt.client.email'),
            'data.client.phone' => __('fiscal-registrar::main.receipt.client.phone'),
            'data.client.name' => __('fiscal-registrar::main.receipt.client.name'),
            'data.client.inn' => __('fiscal-registrar::main.receipt.client.inn'),

            'data.company.name' => __('fiscal-registrar::main.receipt.company.name'),
            'data.company.email' => __('fiscal-registrar::main.receipt.company.email'),
            'data.company.tax_system' => __('fiscal-registrar::main.receipt.company.tax_system'),
            'data.company.inn' => __('fiscal-registrar::main.receipt.company.inn'),
            'data.company.payment_address' => __('fiscal-registrar::main.receipt.company.payment_address'),
            'data.company.payment_site' => __('fiscal-registrar::main.receipt.company.payment_site'),

            'data.agent_info.type' => __('fiscal-registrar::main.receipt.agent_info.type'),
            'data.agent_info.paying_agent.operation' => __('fiscal-registrar::main.receipt.agent_info.paying_agent.operation'),
            'data.agent_info.paying_agent.phones.*' => __('fiscal-registrar::main.receipt.agent_info.paying_agent.phones'),
            'data.agent_info.receive_payments_operator.phones.*' => __('fiscal-registrar::main.receipt.agent_info.receive_payments_operator.phones'),
            'data.agent_info.money_transfer_operator.phones.*' => __('fiscal-registrar::main.receipt.agent_info.money_transfer_operator.phones'),
            'data.agent_info.money_transfer_operator.name' => __('fiscal-registrar::main.receipt.agent_info.money_transfer_operator.name'),
            'data.agent_info.money_transfer_operator.address' => __('fiscal-registrar::main.receipt.agent_info.money_transfer_operator.address'),
            'data.agent_info.money_transfer_operator.inn' => __('fiscal-registrar::main.receipt.agent_info.money_transfer_operator.inn'),

            'data.supplier_info.phones.*' => __('fiscal-registrar::main.receipt.supplier_info.phones'),

            'data.items.*.name' => __('fiscal-registrar::main.receipt.items.name'),
            'data.items.*.price' => __('fiscal-registrar::main.receipt.items.price'),
            'data.items.*.quantity' => __('fiscal-registrar::main.receipt.items.quantity'),
            'data.items.*.sum' => __('fiscal-registrar::main.receipt.items.sum'),
            'data.items.*.measurement_unit' => __('fiscal-registrar::main.receipt.items.measurement_unit'),
            'data.items.*.nomenclature_code' => __('fiscal-registrar::main.receipt.items.nomenclature_code'),
            'data.items.*.payment_method' => __('fiscal-registrar::main.receipt.items.payment_method'),
            'data.items.*.payment_object' => __('fiscal-registrar::main.receipt.items.payment_object'),
            'data.items.*.vat.type' => __('fiscal-registrar::main.receipt.items.vat.type'),
            'data.items.*.vat.sum' => __('fiscal-registrar::main.receipt.items.vat.sum'),
            'data.items.*.agent_info.type' => __('fiscal-registrar::main.receipt.items.agent_info.type'),
            'data.items.*.agent_info.paying_agent.operation' => __('fiscal-registrar::main.receipt.agent_info.paying_agent.operation'),
            'data.items.*.agent_info.paying_agent.phones.*' => __('fiscal-registrar::main.receipt.agent_info.paying_agent.phones'),
            'data.items.*.agent_info.receive_payments_operator.phones.*' => __('fiscal-registrar::main.receipt.agent_info.receive_payments_operator.phones'),
            'data.items.*.agent_info.money_transfer_operator.phones.*' => __('fiscal-registrar::main.receipt.agent_info.money_transfer_operator.phones'),
            'data.items.*.agent_info.money_transfer_operator.name' => __('fiscal-registrar::main.receipt.agent_info.money_transfer_operator.name'),
            'data.items.*.agent_info.money_transfer_operator.address' => __('fiscal-registrar::main.receipt.agent_info.money_transfer_operator.address'),
            'data.items.*.agent_info.money_transfer_operator.inn' => __('fiscal-registrar::main.receipt.agent_info.money_transfer_operator.inn'),
            'data.items.*.supplier_info.phones.*' => __('fiscal-registrar::main.receipt.items.supplier_info.phones'),
            'data.items.*.supplier_info.name' => __('fiscal-registrar::main.receipt.items.supplier_info.name'),
            'data.items.*.supplier_info.inn' => __('fiscal-registrar::main.receipt.items.supplier_info.inn'),
            'data.items.*.user_data' => __('fiscal-registrar::main.receipt.items.user_data'),
            'data.items.*.excise' => __('fiscal-registrar::main.receipt.items.excise'),
            'data.items.*.country_code' => __('fiscal-registrar::main.receipt.items.country_code'),
            'data.items.*.declaration_number' => __('fiscal-registrar::main.receipt.items.declaration_number'),

            'data.payments.cash' => __('fiscal-registrar::main.receipt.payments.cash'),
            'data.payments.electronic' => __('fiscal-registrar::main.receipt.payments.electronic'),
            'data.payments.prepaid' => __('fiscal-registrar::main.receipt.payments.prepaid'),
            'data.payments.postpaid' => __('fiscal-registrar::main.receipt.payments.postpaid'),
            'data.payments.other' => __('fiscal-registrar::main.receipt.payments.other'),

            'data.vats.vat20' => __('fiscal-registrar::main.receipt.vats.vat20'),
            'data.vats.vat10' => __('fiscal-registrar::main.receipt.vats.vat10'),
            'data.vats.with_vat0' => __('fiscal-registrar::main.receipt.vats.with_vat0'),
            'data.vats.without_vat' => __('fiscal-registrar::main.receipt.vats.without_vat'),
            'data.vats.vat120' => __('fiscal-registrar::main.receipt.vats.vat120'),
            'data.vats.vat110' => __('fiscal-registrar::main.receipt.vats.vat110'),

            'data.total' => __('fiscal-registrar::main.receipt.total'),
            'data.additional_check_props' => __('fiscal-registrar::main.receipt.additional_check_props'),
            'data.cashier' => __('fiscal-registrar::main.receipt.cashier'),
            'data.additional_user_props.name' => __('fiscal-registrar::main.receipt.additional_user_props.name'),
            'data.additional_user_props.value' => __('fiscal-registrar::main.receipt.additional_user_props.value'),
        ];
    }
}
