<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Console;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableCellStyle;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Helper\TableStyle;
use TTBooking\FiscalRegistrar\DTO\Receipt\Item;
use TTBooking\FiscalRegistrar\Enums\PaymentType;
use TTBooking\FiscalRegistrar\Models\Receipt;

trait ReceiptRenderer
{
    public function receipt(Receipt $receipt): void
    {
        $table = $this->createReceiptTable();

        static::setupReceiptTableTitle($table, $receipt->result->payload->fiscal_receipt_number ?? null);
        static::setupReceiptTableHeader($table, $receipt);
        foreach ($receipt->data->items as $item) {
            static::addReceiptTableItem($table, $item);
        }
        static::setupReceiptTableTotal($table, $receipt);
        if (isset($receipt->result->payload)) {
            static::setupReceiptTableFooter($table, $receipt);
        }

        $table->render();
    }

    protected function createReceiptTable(): Table
    {
        return (new Table($this->output))
            ->setStyle(static::getReceiptTableStyle())
            ->setColumnWidths([30, 20])
            ->setColumnStyle(1, (new TableStyle)->setPadType(STR_PAD_LEFT));
    }

    protected static function getReceiptTableStyle(): TableStyle
    {
        return (new TableStyle)
            ->setHorizontalBorderChars('═', '─')
            ->setVerticalBorderChars('║', ' ')
            ->setCrossingChars('─', '╔', '═', '╗', '╢', '╝', '═', '╚', '╟', '╠', '═', '╣');
    }

    protected static function setupReceiptTableTitle(Table $table, int $number = null): Table
    {
        $number = isset($number) ? ' '.static::trans('shared.#').$number : '';

        return $table->setHeaders([new TableCell(
            '<comment>'.Str::upper(static::trans('receipt.title')).$number.'</comment>',
            ['colspan' => 2, 'style' => new TableCellStyle(['align' => 'center'])]
        )]);
    }

    protected static function setupReceiptTableHeader(Table $table, Receipt $receipt): Table
    {
        return $table->setRows([
            [$receipt->operation?->getDescription() ?? '-', $receipt->result?->payload->receipt_datetime->format('d.m.Y H:i') ?? '-'],
            [static::trans('result.shift_number'), $receipt->result?->payload->shift_number ?? '-'],
            [static::trans('receipt.company.tax_system'), $receipt->data->company?->tax_system->getDescription('short') ?? '-'],
            [static::trans('receipt.client.phone_or_email'), $receipt->data->client->email ?? $receipt->data->client->phone],
            [static::trans('receipt.company.email'), $receipt->data->company->email ?? '-'],
            [static::trans('result.device_code'), $receipt->result?->extra->device_code ?? '-'],
            [static::trans('result.online_attribute'), static::trans('shared.yes')],
            new TableSeparator,
        ]);
    }

    protected static function addReceiptTableItem(Table $table, Item $item): Table
    {
        return $table->addRows([
            [new TableCell('<info>'.$item->name.'</info>', ['colspan' => 2])],
            ['', sprintf('%d x %.2f', $item->quantity, $item->price)],
            [static::trans('receipt.items.sum'), sprintf('%.2f', $item->sum)],
            [static::trans('receipt.items.payment_object'), $item->payment_object->getDescription()],
            [static::trans('receipt.items.payment_method'), $item->payment_method->getDescription()],
            new TableSeparator,
        ]);
    }

    protected static function setupReceiptTableTotal(Table $table, Receipt $receipt): Table
    {
        return $table->addRows([
            [PaymentType::Cash()->getDescription(), sprintf('%.2f', $receipt->data->payments[PaymentType::Cash] ?? 0)],
            [PaymentType::Electronic()->getDescription(), sprintf('%.2f', $receipt->data->payments[PaymentType::Electronic] ?? 0)],
            [PaymentType::Prepaid()->getDescription(), sprintf('%.2f', $receipt->data->payments[PaymentType::Prepaid] ?? 0)],
            [PaymentType::Postpaid()->getDescription(), sprintf('%.2f', $receipt->data->payments[PaymentType::Postpaid] ?? 0)],
            [PaymentType::Other()->getDescription(), sprintf('%.2f', $receipt->data->payments[PaymentType::Other] ?? 0)],
            [static::trans('receipt.total'), sprintf('%.2f', $receipt->data->total)],
        ]);
    }

    protected static function setupReceiptTableFooter(Table $table, Receipt $receipt): Table
    {
        return $table->addRows([
            new TableSeparator,
            [static::trans('result.fn_number'), $receipt->result->payload->fn_number],
            [static::trans('result.ecr_registration_number'), $receipt->result->payload->ecr_registration_number],
            [static::trans('result.fiscal_document_number'), $receipt->result->payload->fiscal_document_number],
            [static::trans('result.fiscal_document_attribute'), $receipt->result->payload->fiscal_document_attribute],
            [static::trans('result.ffd_version'), '1.05'],
        ]);
    }

    protected static function trans(string $key): string
    {
        return Lang::get('fiscal-registrar::main.'.$key);
    }
}
