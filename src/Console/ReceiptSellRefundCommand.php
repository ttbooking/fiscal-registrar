<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use TTBooking\FiscalRegistrar\Contracts\ReceiptFactory;
use TTBooking\FiscalRegistrar\Enums\Operation;

#[AsCommand(
    name: 'receipt:sell-refund',
    description: 'Register sell refund receipt',
)]
class ReceiptSellRefundCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'receipt:sell-refund
        {id : Receipt identifier}
        {--for= : Connection name}
        {--as= : New identifier}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register sell refund receipt';

    /**
     * Execute the console command.
     */
    public function handle(ReceiptFactory $receipt): void
    {
        $receipt
            ->resolve($this->argument('id'))
            ->for($this->option('for'))
            ->as($this->option('as'))
            ->register(Operation::SellRefund);

        $this->info('Sell refund receipt successfully registered.');
    }
}
