<x-mail::message>
# {{ ucfirst(__('fiscal-registrar::main.receipt.title')) }}{{ isset($receipt->result->payload->fiscal_receipt_number) ? ' '.__('fiscal-registrar::main.shared.#').$receipt->result->payload->fiscal_receipt_number : '' }}

{{ __('fiscal-registrar::main.notification.intro') }}

{!! $receiptHtml !!}
@isset($receipt->result->payload->ofd_receipt_url)

<x-mail::button :url="$receipt->result->payload->ofd_receipt_url">
{{ __('fiscal-registrar::main.notification.view_receipt') }}
</x-mail::button>
@endisset

{{ $receipt->payload->company->name ?? config('app.name') }}
</x-mail::message>
