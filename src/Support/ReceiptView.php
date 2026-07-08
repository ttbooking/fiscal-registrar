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
        $connectionConfig = static::connectionConfig($receipt);

        $template = $connectionConfig['receipt_template'] ?? (
            View::exists("fiscal-registrar::receipt.{$receipt->connection}") ? $receipt->connection : 'default'
        );

        return View::make("fiscal-registrar::receipt.$template", compact('receipt', 'connectionConfig'));
    }

    /**
     * Render the bare receipt markup for embedding into other documents,
     * e.g. the client notification mail.
     */
    public static function body(Receipt $receipt): string
    {
        $connectionConfig = static::connectionConfig($receipt);

        $html = View::make('fiscal-registrar::receipt.body', compact('receipt', 'connectionConfig'))->render();

        // Markdown mail passes raw HTML through CommonMark, which terminates
        // an HTML block at the first blank line - so none may remain.
        return preg_replace('/^\h*\v/m', '', $html) ?? $html;
    }

    /**
     * @return array<string, mixed>
     */
    protected static function connectionConfig(Receipt $receipt): array
    {
        /** @var array<string, mixed> */
        return config("fiscal-registrar.connections.{$receipt->connection}", []) ?? [];
    }
}
