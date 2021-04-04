<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Str;
use TTBooking\FiscalRegistrar\DTO\EventPayload;
use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\DTO\Result;
use TTBooking\FiscalRegistrar\Enums\Operation;

abstract class ReceiptEvent implements ShouldBroadcast
{
    public EventPayload $receipt;

    protected ?string $broadcastAs;

    /**
     * Create a new event instance.
     *
     * @param  string  $connection
     * @param  Operation  $operation
     * @param  string  $externalId
     * @param  string|null  $internalId
     * @param  Receipt|null  $data
     * @param  Result|null  $result
     */
    public function __construct(
        string $connection,
        Operation $operation,
        string $externalId,
        string $internalId = null,
        Receipt $data = null,
        Result $result = null
    ) {
        $this->receipt = EventPayload::new(...func_get_args());
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
        return $this->receipt->toArray();
    }
}
