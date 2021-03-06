<style type="text/css">
.receipt {
    border-collapse: collapse;
    font-family: Arial, Helvetica, sans-serif;
}
.receipt thead {
    text-align: center;
}
.receipt th, .receipt td {
    padding: 5px 10px;
}
.receipt tr:first-child th,
.receipt tr:first-child td {
    padding-top: 10px;
}
.receipt tr:last-child th,
.receipt tr:last-child td {
    padding-bottom: 10px;
}
.receipt > tbody {
    border-top: 1px solid lightgrey;
}
.receipt > tbody th {
    text-align: left;
    font-weight: normal;
    color: darkblue;
}
.receipt > tbody td:last-child {
    text-align: right;
}
</style>

<table class="receipt">
    <thead>
        @php
            $number = $receipt->result?->payload->fiscal_receipt_number ?? null;
            $number = isset($number) ? ' '.__('fiscal-registrar::main.shared.#').$number : '';
        @endphp
        <tr><td colspan="2">{{ $receipt->data->company->name ?? '-' }}</td></tr>
        <tr><td colspan="2">{{ $receipt->data->company->payment_address ?? '-' }}</td></tr>
        <tr><td colspan="2">{{ __('fiscal-registrar::main.receipt.company.inn').' '.($receipt->data->company->inn ?? '-') }}</td></tr>
        <tr><td colspan="2">{{ __('fiscal-registrar::main.receipt.company.payment_site').': '.($receipt->data->company->payment_site ?? '-') }}</td></tr>
        <tr><th colspan="2">{{ Str::upper(__('fiscal-registrar::main.receipt.title')).$number }}</th></tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $receipt->operation?->getDescription() ?? '-' }}</td>
            <td>{{ $receipt->result?->payload->receipt_datetime->format('d.m.Y H:i') ?? '-' }}</td>
        </tr>
        <tr>
            <td>{{ __('fiscal-registrar::main.result.shift_number') }}</td>
            <td>{{ $receipt->result?->payload->shift_number ?? '-' }}</td>
        </tr>
        <tr>
            <td>{{ __('fiscal-registrar::main.receipt.company.tax_system') }}</td>
            <td>{{ $receipt->data->company->tax_system?->getDescription('short') ?? '-' }}</td>
        </tr>
        <tr>
            <td>{{ __('fiscal-registrar::main.receipt.client.phone_or_email') }}</td>
            <td>{{ $receipt->data->client->email ?? $receipt->data->client->phone }}</td>
        </tr>
        <tr>
            <td>{{ __('fiscal-registrar::main.receipt.company.email') }}</td>
            <td>{{ $receipt->data->company->email ?? '-' }}</td>
        </tr>
        <tr>
            <td>{{ __('fiscal-registrar::main.result.device_code') }}</td>
            <td>{{ $receipt->result?->extra->device_code ?? '-' }}</td></tr>
        <tr>
            <td>{{ __('fiscal-registrar::main.result.online_attribute') }}</td>
            <td>{{ __('fiscal-registrar::main.shared.yes') }}</td>
        </tr>
    </tbody>
    @foreach ($receipt->data->items as $item)
    <tbody>
        <tr><th colspan="2">{{ $item->name }}</th></tr>
        <tr><td></td><td>{{ sprintf('%d x %.2f', $item->quantity, $item->price) }}</td></tr>
        <tr>
            <td>{{ __('fiscal-registrar::main.receipt.items.sum') }}</td>
            <td>{{ sprintf('%.2f', $item->sum) }}</td>
        </tr>
        <tr>
            <td>{{ __('fiscal-registrar::main.receipt.items.vat.type') }}</td>
            <td>{{ $item->vat->type->getDescription('short') }}</td>
        </tr>
        @if ($itemVatSum = $item->getVatSum())
        <tr>
            <td>{{ __('fiscal-registrar::main.receipt.items.vat.sum') }}</td>
            <td>{{ sprintf('%.2f', $itemVatSum) }}</td>
        </tr>
        @endif
        <tr>
            <td>{{ __('fiscal-registrar::main.receipt.items.payment_object') }}</td>
            <td>{{ $item->payment_object->getDescription() }}</td>
        </tr>
        <tr>
            <td>{{ __('fiscal-registrar::main.receipt.items.payment_method') }}</td>
            <td>{{ $item->payment_method->getDescription() }}</td>
        </tr>
    </tbody>
    @endforeach
    <tbody>
        <tr>
            <td>{{ __('fiscal-registrar::main.receipt.total') }}</td>
            <td>{{ sprintf('%.2f', $receipt->data->total) }}</td>
        </tr>
        <tr>
            <td>{{ __('fiscal-registrar::main.receipt.payments.cash') }}</td>
            <td>{{ sprintf('%.2f', $receipt->data->payments->cash) }}</td>
        </tr>
        <tr>
            <td>{{ __('fiscal-registrar::main.receipt.payments.electronic') }}</td>
            <td>{{ sprintf('%.2f', $receipt->data->payments->electronic) }}</td>
        </tr>
        <tr>
            <td>{{ __('fiscal-registrar::main.receipt.payments.prepaid') }}</td>
            <td>{{ sprintf('%.2f', $receipt->data->payments->prepaid) }}</td>
        </tr>
        <tr>
            <td>{{ __('fiscal-registrar::main.receipt.payments.postpaid') }}</td>
            <td>{{ sprintf('%.2f', $receipt->data->payments->postpaid) }}</td>
        </tr>
        <tr>
            <td>{{ __('fiscal-registrar::main.receipt.payments.other') }}</td>
            <td>{{ sprintf('%.2f', $receipt->data->payments->other) }}</td>
        </tr>
        @php ($vats = $receipt->data->getVats())
        @if ($vats->vat20)
        <tr>
            <td>{{ __('fiscal-registrar::main.receipt.vats.vat20') }}</td>
            <td>{{ sprintf('%.2f', $vats->vat20) }}</td>
        </tr>
        @endif
        @if ($vats->vat10)
            <tr>
                <td>{{ __('fiscal-registrar::main.receipt.vats.vat10') }}</td>
                <td>{{ sprintf('%.2f', $vats->vat10) }}</td>
            </tr>
        @endif
        @if ($vats->with_vat0)
            <tr>
                <td>{{ __('fiscal-registrar::main.receipt.vats.with_vat0') }}</td>
                <td>{{ sprintf('%.2f', $vats->with_vat0) }}</td>
            </tr>
        @endif
        @if ($vats->without_vat)
            <tr>
                <td>{{ __('fiscal-registrar::main.receipt.vats.without_vat') }}</td>
                <td>{{ sprintf('%.2f', $vats->without_vat) }}</td>
            </tr>
        @endif
        @if ($vats->vat120)
            <tr>
                <td>{{ __('fiscal-registrar::main.receipt.vats.vat120') }}</td>
                <td>{{ sprintf('%.2f', $vats->vat120) }}</td>
            </tr>
        @endif
        @if ($vats->vat110)
            <tr>
                <td>{{ __('fiscal-registrar::main.receipt.vats.vat110') }}</td>
                <td>{{ sprintf('%.2f', $vats->vat110) }}</td>
            </tr>
        @endif
    </tbody>
    <tbody>
        <tr>
            <td>{{ __('fiscal-registrar::main.result.fn_number') }}</td>
            <td>{{ $receipt->result?->payload->fn_number ?? '-' }}</td>
        </tr>
        <tr>
            <td>{{ __('fiscal-registrar::main.result.ecr_registration_number') }}</td>
            <td>{{ $receipt->result?->payload->ecr_registration_number ?? '-' }}</td>
        </tr>
        <tr>
            <td>{{ __('fiscal-registrar::main.result.fiscal_document_number') }}</td>
            <td>{{ $receipt->result?->payload->fiscal_document_number ?? '-' }}</td>
        </tr>
        <tr>
            <td>{{ __('fiscal-registrar::main.result.fiscal_document_attribute') }}</td>
            <td>{{ $receipt->result?->payload->fiscal_document_attribute ?? '-' }}</td>
        </tr>
        <tr>
            <td>{{ __('fiscal-registrar::main.result.ffd_version') }}</td>
            <td>1.05</td>
        </tr>
    </tbody>
</table>
