<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\VonageSmsChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;
use TTBooking\FiscalRegistrar\Models\Receipt;
use TTBooking\FiscalRegistrar\Support\ReceiptView;

class ReceiptProcessed extends Notification implements ShouldQueue
{
    use Queueable;

    protected Receipt $receipt;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Receipt $receipt)
    {
        $this->receipt = $receipt;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return string[]
     */
    public function via(mixed $notifiable): array
    {
        return match (true) {
            is_object($notifiable) && isset($notifiable->routes['mail']) => ['mail'],
            is_object($notifiable) && isset($notifiable->routes['vonage'])
                && class_exists(VonageSmsChannel::class) => ['vonage'],
            default => [],
        };
    }

    /**
     * Get the mail representation of the notification.
     *
     * The mail body is the same receipt render the preview endpoint
     * serves, honoring per-connection receipt_template configuration.
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        $view = ReceiptView::for($this->receipt);

        return (new MailMessage)
            ->subject((string) __('fiscal-registrar::main.notification.subject'))
            ->view($view->name(), $view->getData());
    }

    /**
     * Get the Vonage / SMS representation of the notification.
     */
    public function toVonage(mixed $notifiable): VonageMessage
    {
        return (new VonageMessage)
            ->content((string) __('fiscal-registrar::main.notification.sms', [
                'total' => number_format((float) $this->receipt->payload->total, 2, '.', ' '),
            ]))
            ->unicode();
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<mixed>
     */
    public function toArray(mixed $notifiable): array
    {
        return $this->receipt->toArray();
    }
}
