<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Console;

use Illuminate\Console\Command;
use TTBooking\FiscalRegistrar\Models\Receipt;

class ReceiptDeleteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'receipt:delete {id : Receipt identifier}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete receipt';

    /**
     * Execute the console command.
     *
     * @param  Receipt  $receipt
     * @return void
     */
    public function handle(Receipt $receipt)
    {
        $receipt->resolve($this->argument('id'))->delete();

        $this->info('Receipt successfully deleted.');
    }
}
