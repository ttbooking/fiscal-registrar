<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\DTO\Result;

class ReceiptEvent implements ShouldBroadcast
{
    public string $connection;

    public string $operation;

    public string $externalId;

    public string $internalId;

    public Receipt $receipt;

    public Result $result;

    /**
     * Create a new event instance.
     *
     * @param  string  $connection
     * @param  string  $operation
     * @param  string  $externalId
     * @param  string  $internalId
     * @param  Receipt  $receipt
     * @param  Result  $result
     */
    public function __construct(
        string $connection,
        string $operation,
        string $externalId,
        string $internalId,
        Receipt $receipt,
        Result $result
    ) {
        $this->connection = $connection;
        $this->operation = $operation;
        $this->externalId = $externalId;
        $this->internalId = $internalId;
        $this->receipt = $receipt;
        $this->result = $result;
    }

    public function broadcastOn()
    {
        return new Channel('fiscal-registrar');
    }
}
