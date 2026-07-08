<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Tests;

use Illuminate\Notifications\AnonymousNotifiable;
use TTBooking\FiscalRegistrar\DTO;
use TTBooking\FiscalRegistrar\Models\Receipt;
use TTBooking\FiscalRegistrar\Notifications\ReceiptProcessed;

class ReceiptProcessedNotificationTest extends TestCase
{
    protected function makeReceipt(): Receipt
    {
        $receipt = new Receipt;
        $receipt->payload = DTO\Receipt::from([
            'client' => ['email' => 'client@example.com'],
            'items' => [['name' => 'Product', 'price' => 100, 'vat' => ['type' => 'vat22']]],
        ]);

        return $receipt;
    }

    public function test_mail_notification_embeds_receipt_render(): void
    {
        $mail = (new ReceiptProcessed($this->makeReceipt()))->toMail(new AnonymousNotifiable);

        $rendered = (string) $mail->render();

        // Intro from the mail template wrapper
        $this->assertStringContainsString(__('fiscal-registrar::main.notification.intro'), $rendered);

        // Embedded receipt render survives the markdown pipeline intact
        $this->assertStringContainsString('id="receipt"', $rendered);
        $this->assertStringContainsString('Product', $rendered);
        $this->assertStringContainsString('100.00', $rendered);
        $this->assertStringNotContainsString('<code>', $rendered);
    }

    public function test_via_selects_mail_channel_by_route(): void
    {
        $notification = new ReceiptProcessed($this->makeReceipt());

        $this->assertSame(
            ['mail'],
            $notification->via((new AnonymousNotifiable)->route('mail', 'client@example.com'))
        );
        $this->assertSame([], $notification->via(new AnonymousNotifiable));
    }
}
