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
     * @param  string  $name
     * @param  ReceiptEvent[]  $event
     * @return void
     */
    public function handle(string $name, array $event)
    {
        FiscalRecord::query()->updateOrCreate([
            'connection' => $event[0]->connection,
            'external_id' => $event[0]->externalId,
            'receipt' => $event[0]->receipt,
        ]);
    }
}
