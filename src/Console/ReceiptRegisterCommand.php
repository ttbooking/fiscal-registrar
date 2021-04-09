<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Console;

use Illuminate\Console\Command;
use TTBooking\FiscalRegistrar\Contracts\ReceiptFactory;

abstract class ReceiptRegisterCommand extends Command
{
    /**
     * Execute the console command.
     *
     * @param  ReceiptFactory  $receipt
     * @return void
     */
    public function handle(ReceiptFactory $receipt)
    {

    }
}
