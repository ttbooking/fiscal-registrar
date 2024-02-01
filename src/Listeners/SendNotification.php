<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\AnonymousNotifiable;
use TTBooking\FiscalRegistrar\Events\Processed;
use TTBooking\FiscalRegistrar\Notifications\ReceiptProcessed;

class SendNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(protected AnonymousNotifiable $notifiable)
    {
    }

    /**
     * Handle the event.
     */
    public function handle(Processed $event): void
    {
        $this->notifiable
            ->route('mail', $event->receipt->payload->client->email)
            ->route('nexmo', $event->receipt->payload->client->phone)
            ->notify(new ReceiptProcessed($event->receipt));
    }

    /**
     * Get the tags that should be assigned to the queued listener.
     *
     * @return string[]
     */
    public function tags(): array
    {
        return ['fiscal-registrar'];
    }
}
