<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Console;

use Illuminate\Console\Command;
use TTBooking\FiscalRegistrar\Contracts\ReceiptFactory;

class ReceiptShowCommand extends Command
{
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
     *
     * @param  ReceiptFactory  $receipt
     * @return void
     */
    public function handle(ReceiptFactory $receipt)
    {
        $receipt = $receipt->resolve($this->argument('id'))->getModel();

        foreach ($receipt->data->items as $item) {
            $this->table([$item->name, ''], [
                ['', sprintf('%d x %.2f', $item->quantity, $item->price)],
                ['стоимость', sprintf('%.2f', $item->sum)],
                ['предмет расчета', $item->payment_object->getDescription()],
                ['способ расчета', $item->payment_method->getDescription()],
            ], 'compact');
        }
    }
}
