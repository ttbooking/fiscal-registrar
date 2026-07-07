<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Support;

use Illuminate\Support\Facades\View;
use TTBooking\FiscalRegistrar\Models\Receipt;

class ReceiptView
{
    /**
     * Resolve receipt view for the given receipt model, honoring
     * the connection's receipt_template configuration option.
     */
    public static function for(Receipt $receipt): \Illuminate\Contracts\View\View
    {
        /** @var array{receipt_template?: string} $connectionConfig */
        $connectionConfig = config("fiscal-registrar.connections.{$receipt->connection}", []) ?? [];

        $template = $connectionConfig['receipt_template'] ?? (
            View::exists("fiscal-registrar::receipt.{$receipt->connection}") ? $receipt->connection : 'default'
        );

        return View::make("fiscal-registrar::receipt.$template", compact('receipt', 'connectionConfig'));
    }
}
