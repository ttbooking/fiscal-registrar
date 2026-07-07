<x-mail::message>
# {{ ucfirst(__('fiscal-registrar::main.receipt.title')) }}{{ isset($receipt->result->payload->fiscal_receipt_number) ? ' '.__('fiscal-registrar::main.shared.#').$receipt->result->payload->fiscal_receipt_number : '' }}

{{ __('fiscal-registrar::main.notification.intro') }}

<x-mail::table>
| {{ __('fiscal-registrar::main.notification.item') }} | {{ __('fiscal-registrar::main.notification.quantity') }} | {{ __('fiscal-registrar::main.notification.amount') }} |
|:--|--:|--:|
@foreach ($receipt->payload->items as $item)
| {{ $item->name }} | {{ $item->quantity }} | {{ number_format($item->sum, 2, '.', ' ') }} |
@endforeach
| **{{ __('fiscal-registrar::main.notification.total') }}** | | **{{ number_format($receipt->payload->total, 2, '.', ' ') }}** |
</x-mail::table>
@isset($receipt->result->payload->ofd_receipt_url)

<x-mail::button :url="$receipt->result->payload->ofd_receipt_url">
{{ __('fiscal-registrar::main.notification.view_receipt') }}
</x-mail::button>
@endisset

{{ $receipt->payload->company->name ?? config('app.name') }}
</x-mail::message>
