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
     * @param  Receipt  $receipt
     * @return void
     */
    public function __construct(Receipt $receipt)
    {
        $this->receipt = $receipt;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        switch (true) {
            case isset($notifiable->routes['mail']): return ['mail'];
            case isset($notifiable->routes['nexmo']): return ['nexmo'];
            default: return [];
        }
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)->markdown('fiscal-registrar::receipt', ['receipt' => $this->receipt]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->receipt->toArray();
    }
}
