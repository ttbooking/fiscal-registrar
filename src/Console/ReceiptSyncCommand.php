<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Console;

use Illuminate\Console\Command;
use TTBooking\FiscalRegistrar\Contracts\ReceiptFactory;

class ReceiptSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'receipt:sync
        {id : Receipt identifier}
        {--force : Force synchronization}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize receipt';

    /**
     * Execute the console command.
     *
     * @param  ReceiptFactory  $receipt
     * @return void
     */
    public function handle(ReceiptFactory $receipt)
    {
        $receipt
            ->resolve($this->argument('id'))
            ->report(null, $this->option('force'));

        $this->info('Receipt successfully synchronized.');
    }
}
