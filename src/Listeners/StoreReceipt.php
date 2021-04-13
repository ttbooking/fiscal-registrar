<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Listeners;

use TTBooking\FiscalRegistrar\Events\RegistrationEvent;
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
     * @param  RegistrationEvent  $event
     * @return void
     */
    public function handle(RegistrationEvent $event): void
    {
        $this->receipt->newQuery()->updateOrCreate([
            'connection' => $event->reg_conn,
            'external_id' => $event->external_id,
        ], [
            'operation' => $event->operation,
            'internal_id' => $event->internal_id ?? null,
            'data' => $event->data,
        ]);
    }
}
