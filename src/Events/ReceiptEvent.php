<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use TTBooking\FiscalRegistrar\Models\Receipt;

abstract class ReceiptEvent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public Receipt $receipt;

    protected ?string $broadcastAs = null;

    /**
     * Create a new event instance.
     */
    public function __construct(Receipt $receipt)
    {
        $this->receipt = $receipt;
    }

    public function broadcastAs(): string
    {
        return 'receipt.'.($this->broadcastAs ?? Str::kebab(class_basename(static::class)));
    }

    public function broadcastOn()
    {
        return new Channel('fiscal-registrar');
    }

    /**
     * @return array<mixed>
     */
    public function broadcastWith(): array
    {
        return $this->receipt->toArray();
    }
}
