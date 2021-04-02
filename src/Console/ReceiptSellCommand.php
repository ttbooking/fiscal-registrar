<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Console;

use TTBooking\FiscalRegistrar\Models\Receipt;

class ReceiptSellCommand extends ReceiptRegisterCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'receipt:sell {id : Receipt identifier}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register sell receipt';

    /**
     * Execute the console command.
     *
     * @param  Receipt  $receipt
     * @return void
     */
    public function handle(Receipt $receipt)
    {
        parent::handle($receipt);

        $this->info('Sell receipt successfully registered.');
    }
}
