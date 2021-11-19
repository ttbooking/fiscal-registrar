<!DOCTYPE html>
<html lang="{{ config('app.locale', 'en') }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <title>{{ __('fiscal-registrar::main.title') }}{{ config('app.name') ? ' - ' . config('app.name') : '' }}</title>

    <style>
        @media print {
            html {
                height: 100%;
            }
            body {
                transform: scale(.5);
                transform-origin: top left;
            }
            #receipt {
                border: 1px solid black;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            #receipt tfoot {
                break-inside: avoid;
            }
        }

        body {
            margin: 0;
        }
        #receipt {
            @if ($connectionConfig['test'] ?? false)
            background-color: lightyellow;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' version='1.1' height='120px' width='120px'><text transform='translate(30, 100) rotate(-45)' fill='lightgray' font-family='Arial, Helvetica, sans-serif' font-size='32'>{{ __('fiscal-registrar::main.shared.sample') }}</text></svg>");
            @endif
            width: -moz-fit-content;
            width: fit-content;
            padding: 5px;
        }
        #receipt table {
            border-collapse: collapse;
            font-family: Arial, Helvetica, sans-serif;
        }
        #receipt thead, #receipt tfoot {
            display: table-row-group;
            text-align: center;
        }
        #receipt th, #receipt td {
            padding: 5px 10px;
        }
        #receipt tr:first-child th,
        #receipt tr:first-child td {
            padding-top: 10px;
        }
        #receipt tr:last-child th,
        #receipt tr:last-child td {
            padding-bottom: 10px;
        }
        #receipt table > tbody {
            border-top: 1px solid lightgrey;
        }
        #receipt table > tbody th {
            text-align: left;
            font-weight: normal;
            color: darkblue;
        }
        #receipt table > tbody td:last-child {
            text-align: right;
        }
    </style>
</head>
<body>
<div id="receipt">
    <table>
        <thead>
        @php
            $number = $receipt->result?->payload->fiscal_receipt_number ?? null;
            $number = isset($number) ? ' '.__('fiscal-registrar::main.shared.#').$number : '';
        @endphp
        <tr><td colspan="2">{{ $receipt->data->company->name ?? $connectionConfig['company']['name'] ?? '-' }}</td></tr>
        <tr><td colspan="2">{{ $receipt->data->company->payment_address ?? $connectionConfig['company']['payment_address'] ?? '-' }}</td></tr>
        <tr><td colspan="2">{{ __('fiscal-registrar::main.receipt.company.inn').' '.($receipt->data->company->inn ?? $connectionConfig['company']['inn'] ?? '-') }}</td></tr>
        <tr><td colspan="2">{{ __('fiscal-registrar::main.receipt.company.payment_site').': '.($receipt->data->company->payment_site ?? $connectionConfig['company']['payment_site'] ?? '-') }}</td></tr>
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
            <td>{{ $receipt->data->company->tax_system?->getDescription('short') ?? isset($connectionConfig['company']['tax_system']) ? __('fiscal-registrar::enum.tax_system_short.'.$connectionConfig['company']['tax_system']) : '-' }}</td>
        </tr>
        <tr>
            <td>{{ __('fiscal-registrar::main.receipt.client.phone_or_email') }}</td>
            <td>{{ $receipt->data->client->email ?? $receipt->data->client->phone }}</td>
        </tr>
        <tr>
            <td>{{ __('fiscal-registrar::main.receipt.company.email') }}</td>
            <td>{{ $receipt->data->company->email ?? $connectionConfig['company']['email'] ?? '-' }}</td>
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
        @if ($receipt->result)
            <tfoot>
            <tr>
                <td colspan="2"><img src="{{ ReceiptQRCode::for($receipt->result->payload, $receipt->operation)->png()->getDataUri() }}" /></td>
            </tr>
            </tfoot>
        @endif
    </table>
</div>
</body>
