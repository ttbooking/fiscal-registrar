<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use TTBooking\FiscalRegistrar\Contracts\ReceiptFactory;

#[AsCommand(
    name: 'receipt:delete',
    description: 'Delete receipt',
)]
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
     */
    public function handle(ReceiptFactory $receipt): void
    {
        $receipt->resolve($this->argument('id'))->delete();

        $this->info('Receipt successfully deleted.');
    }
}
