<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Events;

use TTBooking\FiscalRegistrar\DTO\Receipt;

abstract class ReceiptEvent
{
    /** @var Receipt */
    protected Receipt $receipt;

    /**
     * Create a new event instance.
     *
     * @param Receipt $receipt
     */
    public function __construct(Receipt $receipt)
    {
        $this->receipt = $receipt;
    }

    public function getReceipt(): Receipt
    {
        return $this->receipt;
    }
}
