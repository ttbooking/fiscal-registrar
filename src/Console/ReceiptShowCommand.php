<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use TTBooking\FiscalRegistrar\Contracts\ReceiptFactory;

#[AsCommand(
    name: 'receipt:show',
    description: 'Show receipt',
)]
class ReceiptShowCommand extends Command
{
    use ReceiptRenderer;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'receipt:show {id : Receipt identifier}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show receipt';

    /**
     * Execute the console command.
     */
    public function handle(ReceiptFactory $receipt): void
    {
        $this->receipt($receipt->resolve($this->argument('id'))->getModel());
    }
}
