<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Console;

use Illuminate\Console\Command;
use TTBooking\FiscalRegistrar\Models\Receipt;

abstract class ReceiptRegisterCommand extends Command
{
    /**
     * Execute the console command.
     *
     * @param  Receipt  $receipt
     * @return void
     */
    public function handle(Receipt $receipt)
    {

    }
}
