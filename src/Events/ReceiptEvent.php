<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Events;

use TTBooking\FiscalRegistrar\DTO\Receipt;

final class ReceiptEvent
{
    public string $connection;

    public string $externalId;

    public Receipt $receipt;

    /**
     * Create a new event instance.
     *
     * @param  string  $connection
     * @param  string  $externalId
     * @param  Receipt  $receipt
     */
    public function __construct(string $connection, string $externalId, Receipt $receipt)
    {
        $this->connection = $connection;
        $this->externalId = $externalId;
        $this->receipt = $receipt;
    }
}
