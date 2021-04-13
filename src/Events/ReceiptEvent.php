<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Str;

abstract class ReceiptEvent implements ShouldBroadcast
{
    protected ?string $broadcastAs;

    public function broadcastAs(): string
    {
        return 'receipt.'.($this->broadcastAs ?? Str::kebab(class_basename(static::class)));
    }

    public function broadcastOn()
    {
        return new Channel('fiscal-registrar');
    }
}
