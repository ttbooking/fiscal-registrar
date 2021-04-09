<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Console;

use TTBooking\FiscalRegistrar\Contracts\ReceiptFactory;

class ReceiptSellRefundCommand extends ReceiptRegisterCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'receipt:sell-refund {id : Receipt identifier}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register sell refund receipt';

    /**
     * Execute the console command.
     *
     * @param  ReceiptFactory  $receipt
     * @return void
     */
    public function handle(ReceiptFactory $receipt)
    {
        parent::handle($receipt);

        $this->info('Sell refund receipt successfully registered.');
    }
}
