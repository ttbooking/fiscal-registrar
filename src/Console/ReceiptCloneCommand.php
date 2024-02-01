<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use TTBooking\FiscalRegistrar\Contracts\ReceiptFactory;

#[AsCommand(
    name: 'receipt:clone',
    description: 'Clone receipt',
)]
class ReceiptCloneCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'receipt:clone {id : Receipt identifier}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clone receipt';

    /**
     * Execute the console command.
     */
    public function handle(ReceiptFactory $receipt): void
    {
        $receipt->resolve($this->argument('id'))->clone()->save();

        $this->info('Receipt successfully cloned.');
    }
}
