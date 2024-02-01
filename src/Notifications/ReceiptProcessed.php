<?php

namespace TTBooking\FiscalRegistrar\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use TTBooking\FiscalRegistrar\Models\Receipt;

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
        switch (true) {
            case is_object($notifiable) && isset($notifiable->routes['mail']): return ['mail'];
            case is_object($notifiable) && isset($notifiable->routes['nexmo']): return ['nexmo'];
            default: return [];
        }
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(mixed $notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        return (new MailMessage)->markdown('fiscal-registrar::receipt', ['receipt' => $this->receipt]);
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
