<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Console;

use Illuminate\Console\Command;
use TTBooking\FiscalRegistrar\Contracts\ReceiptFactory;

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
     * @param  ReceiptFactory  $receipt
     * @return void
     */
    public function handle(ReceiptFactory $receipt)
    {
        $receipt->resolve($this->argument('id'))->delete();

        $this->info('Receipt successfully deleted.');
    }
}
