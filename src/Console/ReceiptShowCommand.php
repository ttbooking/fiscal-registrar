<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableCellStyle;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Helper\TableStyle;
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

        $rightAligned = (new TableStyle)->setPadType(STR_PAD_LEFT);
        //$separatorStyle = (new TableStyle)->setHorizontalBorderChars('-');

        foreach ($receipt->data->items as $item) {
            (new Table($this->output))
                ->setHeaders([$item->name])
                ->setRows([
                    ['', sprintf('%d x %.2f', $item->quantity, $item->price)],
                    ['стоимость', sprintf('%.2f', $item->sum)],
                    ['предмет расчета', $item->payment_object->getDescription()],
                    ['способ расчета', $item->payment_method->getDescription()],
                    [new TableCell(str_repeat('-', 50), ['colspan' => 2])],
                    //new TableSeparator(['style' => $separatorStyle]),
                ])
                ->setStyle('compact')
                ->setColumnWidths([30, 20])
                ->setColumnStyle(1, $rightAligned)
                ->render();
        }
    }
}
