<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Console;

use TTBooking\FiscalRegistrar\Contracts\ReceiptFactory;
use TTBooking\FiscalRegistrar\Enums\Operation;

class ReceiptSellCommand extends ReceiptRegisterCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'receipt:sell
        {id : Receipt identifier}
        {--for= : Connection name}
        {--as= : New identifier}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register sell receipt';

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
            ->for($this->option('for'))
            ->as($this->option('as'))
            ->register(Operation::Sell());

        $this->info('Sell receipt successfully registered.');
    }
}
