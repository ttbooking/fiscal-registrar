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

        $operation = $receipt->operation ?? null;
        $sno = $receipt->data->company->sno ?? null;
        $companyEmail = $receipt->data->company->email ?? '-';

        $table->setRows([
            [isset($operation) ? $operation->getDescription() : '-', $receipt->result->payload->receipt_datetime->format('d.m.Y H:i')],
            [static::trans('result.shift_number'), $receipt->result->payload->shift_number],
            [static::trans('receipt.company.sno'), isset($sno) ? $sno->getDescription('short') : '-'],
            [static::trans('receipt.client.phone_or_email'), $receipt->data->client->email ?? $receipt->data->client->phone],
            [static::trans('receipt.company.email'), $companyEmail],
            [static::trans('result.device_code'), $receipt->result->extra->device_code],
            [static::trans('result.online_attribute'), static::trans('shared.yes')],
            new TableSeparator,
        ]);

        foreach ($receipt->data->items as $item) {
            $table->addRows([
                [new TableCell('<info>'.$item->name.'</info>', ['colspan' => 2])],
                ['', sprintf('%d x %.2f', $item->quantity, $item->price)],
                [static::trans('receipt.items.sum'), sprintf('%.2f', $item->sum)],
                [static::trans('receipt.items.payment_object'), $item->payment_object->getDescription()],
                [static::trans('receipt.items.payment_method'), $item->payment_method->getDescription()],
                new TableSeparator,
            ]);
        }

        $table->addRows([
            [static::trans('receipt.total'), sprintf('%.2f', $receipt->data->total)],
            new TableSeparator,
        ]);

        $table->addRows([
            [static::trans('result.fn_number'), $receipt->result->payload->fn_number],
            [static::trans('result.ecr_registration_number'), $receipt->result->payload->ecr_registration_number],
            [static::trans('result.fiscal_document_number'), $receipt->result->payload->fiscal_document_number],
            [static::trans('result.fiscal_document_attribute'), $receipt->result->payload->fiscal_document_attribute],
            [static::trans('result.ffd_version'), '1.05'],
        ]);

        $table
            ->setHeaders([new TableCell(
                '<comment>'.Str::upper(static::trans('receipt.title'))
                .' '.static::trans('shared.#').$receipt->result->payload->fiscal_receipt_number.'</comment>',
                ['colspan' => 2, 'style' => new TableCellStyle(['align' => 'center'])]
            )])
            ->setStyle($style)
            ->setColumnWidths([30, 20])
            ->setColumnStyle(1, (new TableStyle)->setPadType(STR_PAD_LEFT))
            ->render();
    }

    protected static function trans(string $key): string
    {
        return Lang::get('fiscal-registrar::main.'.$key);
    }
}
