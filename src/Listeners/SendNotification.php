<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Listeners;

use Illuminate\Notifications\AnonymousNotifiable;
use TTBooking\FiscalRegistrar\Events\Processed;
use TTBooking\FiscalRegistrar\Notifications\ReceiptProcessed;

class SendNotification
{
    protected AnonymousNotifiable $notifiable;

    /**
     * Create the event listener.
     *
     * @param  AnonymousNotifiable  $notifiable
     * @return void
     */
    public function __construct(AnonymousNotifiable $notifiable)
    {
        $this->notifiable = $notifiable;
    }

    /**
     * Handle the event.
     *
     * @param  Processed  $event
     * @return void
     */
    public function handle(Processed $event): void
    {
        $this->notifiable
            ->route('mail', $event->receipt->data->client->email)
            ->route('nexmo', $event->receipt->data->client->phone)
            ->notify(new ReceiptProcessed($event->receipt));
    }
}
