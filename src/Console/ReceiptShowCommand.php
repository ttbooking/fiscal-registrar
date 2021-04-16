<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
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

        $table = new Table($this->output);
        $style = (new TableStyle)
            ->setHorizontalBorderChars('═', '─')
            ->setVerticalBorderChars('║', ' ')
            ->setCrossingChars('─', '╔', '═', '╗', '╢', '╝', '═', '╚', '╟', '╠', '═', '╣');

        foreach ($receipt->data->items as $item) {
            $table->addRows([
                [new TableCell("<info>$item->name</info>", ['colspan' => 2])],
                ['', sprintf('%d x %.2f', $item->quantity, $item->price)],
                [static::trans('items.sum'), sprintf('%.2f', $item->sum)],
                [static::trans('items.payment_object'), $item->payment_object->getDescription()],
                [static::trans('items.payment_method'), $item->payment_method->getDescription()],
                new TableSeparator,
            ]);
        }

        $table
            ->setHeaders([new TableCell(
                '<comment>'.Str::upper(static::trans('__self')).'</comment>',
                ['colspan' => 2, 'style' => new TableCellStyle(['align' => 'center'])]
            )])
            ->addRow([static::trans('total'), sprintf('%.2f', $receipt->data->total)])
            ->setStyle($style)
            ->setColumnWidths([30, 20])
            ->setColumnStyle(1, (new TableStyle)->setPadType(STR_PAD_LEFT))
            ->render();
    }

    protected static function trans(string $key): string
    {
        return Lang::get('fiscal-registrar::main.receipt.'.$key);
    }
}
