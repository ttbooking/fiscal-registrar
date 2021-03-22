<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Events;

use TTBooking\FiscalRegistrar\DTO\Receipt;

final class ReceiptEvent
{
    public string $connection;

    public Receipt $receipt;

    public string $externalId;

    public ?string $internalId;

    /**
     * Create a new event instance.
     *
     * @param  string  $connection
     * @param  Receipt  $receipt
     * @param  string  $externalId
     * @param  string|null  $internalId
     */
    public function __construct(string $connection, Receipt $receipt, string $externalId, string $internalId = null)
    {
        $this->connection = $connection;
        $this->receipt = $receipt;
        $this->externalId = $externalId;
        $this->internalId = $internalId;
    }
}
