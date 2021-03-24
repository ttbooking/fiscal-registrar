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
            'connection' => $event->payload->connection,
            'external_id' => $event->payload->externalId,
        ], [
            'operation' => $event->payload->operation,
            'internal_id' => $event->payload->internalId,
            'receipt' => $event->payload->receipt,
            'result' => $event->payload->result,
        ]);
    }
}
