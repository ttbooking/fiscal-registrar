<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Str;
use TTBooking\FiscalRegistrar\DTO\EventPayload;
use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\DTO\Result;

abstract class ReceiptEvent implements ShouldBroadcast
{
    public EventPayload $payload;

    protected ?string $broadcastAs;

    /**
     * Create a new event instance.
     *
     * @param  string  $connection
     * @param  string  $operation
     * @param  string  $externalId
     * @param  string|null  $internalId
     * @param  Receipt|null  $receipt
     * @param  Result|null  $result
     */
    public function __construct(
        string $connection,
        string $operation,
        string $externalId,
        string $internalId = null,
        Receipt $receipt = null,
        Result $result = null
    ) {
        $this->payload = EventPayload::new(...func_get_args());
    }

    public function broadcastAs(): string
    {
        return 'receipt.'.($this->broadcastAs ?? Str::kebab(class_basename(static::class)));
    }

    public function broadcastOn()
    {
        return new Channel('fiscal-registrar');
    }

    public function broadcastWith(): array
    {
        return $this->payload->toArray();
    }
}
