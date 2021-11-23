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
            'data.client.inn' => 'sometimes|nullable|string|numeric|size:10,12',

            'data.company' => 'required|array',
            'data.company.name' => 'sometimes|nullable|string|max:64',
            'data.company.email' => 'sometimes|nullable|string|max:64|email',
            'data.company.tax_system' => ['sometimes', 'nullable', 'string', Rule::in(Enums\TaxSystem::toArray())],
            'data.company.inn' => 'sometimes|nullable|string|numeric',
            'data.company.payment_address' => 'sometimes|nullable|string|max:256',
            'data.company.payment_site' => 'sometimes|nullable|string|max:256',

            'data.agent_info' => 'sometimes|nullable|array',
            'data.agent_info.type' => ['required', 'string', Rule::in(Enums\AgentType::toArray())],
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
            'data.agent_info.money_transfer_operator.inn' => 'sometimes|nullable|string|numeric|size:10,12',

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
            'data.items.*.agent_info.type' => ['required', 'string', Rule::in(Enums\AgentType::toArray())],
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
            'data.items.*.agent_info.money_transfer_operator.inn' => 'sometimes|nullable|string|numeric|size:10,12',
            'data.items.*.supplier_info' => 'required_with:items.*.agent_info|nullable|array',
            'data.items.*.supplier_info.phones' => 'sometimes|nullable|array',
            'data.items.*.supplier_info.phones.*' => 'required|string|max:19',
            'data.items.*.supplier_info.name' => 'sometimes|nullable|string|max:64',
            'data.items.*.supplier_info.inn' => 'sometimes|nullable|string|numeric',
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
            //
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
            //
        ];
    }
}
