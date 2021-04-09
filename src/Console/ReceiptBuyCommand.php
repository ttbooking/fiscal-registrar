<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Console;

use TTBooking\FiscalRegistrar\Contracts\ReceiptFactory;

class ReceiptBuyCommand extends ReceiptRegisterCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'receipt:buy {id : Receipt identifier}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register buy receipt';

    /**
     * Execute the console command.
     *
     * @param  ReceiptFactory  $receipt
     * @return void
     */
    public function handle(ReceiptFactory $receipt)
    {
        parent::handle($receipt);

        $this->info('Buy receipt successfully registered.');
    }
}
