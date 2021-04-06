<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Listeners;

use TTBooking\FiscalRegistrar\Events\ReceiptEvent;
use TTBooking\FiscalRegistrar\Models\Receipt;

class StoreReceipt
{
    protected Receipt $receipt;

    /**
     * Create the event listener.
     *
     * @param  Receipt  $receipt
     * @return void
     */
    public function __construct(Receipt $receipt)
    {
        $this->receipt = $receipt;
    }

    /**
     * Handle the event.
     *
     * @param  ReceiptEvent  $event
     * @return void
     */
    public function handle(ReceiptEvent $event)
    {
        $this->receipt->newQuery()->updateOrCreate([
            'connection' => $event->receipt->connection,
            'external_id' => $event->receipt->external_id,
        ], [
            'operation' => $event->receipt->operation,
            'internal_id' => $event->receipt->internal_id,
            'data' => $event->receipt->data,
            'result' => $event->receipt->result,
        ]);
    }
}
