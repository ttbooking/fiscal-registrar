<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Console;

use Illuminate\Console\Command;
use TTBooking\FiscalRegistrar\Models\Receipt;

class ReceiptCopyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'receipt:copy {id : Receipt identifier}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy receipt';

    /**
     * Execute the console command.
     *
     * @param  Receipt  $receipt
     * @return void
     */
    public function handle(Receipt $receipt)
    {
        $receipt->resolve($this->argument('id'))->replicate()->save();

        $this->info('Receipt successfully copied.');
    }
}
