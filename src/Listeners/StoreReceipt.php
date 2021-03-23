<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Listeners;

use TTBooking\FiscalRegistrar\Events\ReceiptEvent;
use TTBooking\FiscalRegistrar\Models\FiscalRecord;

class StoreReceipt
{
    /**
     * Handle the event.
     *
     * @param  ReceiptEvent  $event
     * @return void
     */
    public function handle(ReceiptEvent $event)
    {
        FiscalRecord::query()->updateOrCreate([
            'connection' => $event->connection,
            'external_id' => $event->externalId,
        ], [
            'operation' => $event->operation,
            'internal_id' => $event->internalId,
            'receipt' => $event->receipt,
            'result' => $event->result,
        ]);
    }
}
