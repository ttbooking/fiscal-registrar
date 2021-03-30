<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Listeners;

use TTBooking\FiscalRegistrar\Contracts\OperatesCustomizableReceipt;
use TTBooking\FiscalRegistrar\Events\ReceiptEvent;
use TTBooking\FiscalRegistrar\Models\Receipt;

class StoreReceipt implements OperatesCustomizableReceipt
{
    protected string $receiptModel;

    /**
     * Create the event listener.
     *
     * @param  string  $receiptModel
     * @return void
     */
    public function __construct(string $receiptModel)
    {
        if (! is_a($receiptModel, Receipt::class, true)) {
            throw new \InvalidArgumentException('Custom receipt model must extend '.Receipt::class.' class.');
        }

        $this->receiptModel = $receiptModel;
    }

    /**
     * Handle the event.
     *
     * @param  ReceiptEvent  $event
     * @return void
     */
    public function handle(ReceiptEvent $event)
    {
        $this->receiptModel::query()->updateOrCreate([
            'connection' => $event->receipt->connection,
            'external_id' => $event->receipt->externalId,
        ], [
            'operation' => $event->receipt->operation,
            'internal_id' => $event->receipt->internalId,
            'data' => $event->receipt->data,
            'result' => $event->receipt->result,
        ]);
    }
}
