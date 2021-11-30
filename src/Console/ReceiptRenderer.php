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
use TTBooking\FiscalRegistrar\Models\Receipt;
use TTBooking\FiscalRegistrar\Support\ReceiptQRCode;

trait ReceiptRenderer
{
    public function receipt(Receipt $receipt): void
    {
        $table = $this->createReceiptTable();

        static::setupReceiptTableTitle($table, $receipt);
        static::setupReceiptTableHeader($table, $receipt);
        foreach ($receipt->payload->items as $item) {
            static::addReceiptTableItem($table, $item);
        }
        static::setupReceiptTableTotal($table, $receipt);
        if (isset($receipt->result->payload)) {
            static::setupReceiptTableFooter($table, $receipt);
            static::setupReceiptTableQRCode($table, $receipt);
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
            ->setCrossingChars('─', '╔', '═', '╗', '╢', '╝', '═', '╚', '╟', '╔', '═', '╗');
    }

    protected static function setupReceiptTableTitle(Table $table, Receipt $receipt): Table
    {
        $options = ['colspan' => 2, 'style' => new TableCellStyle(['align' => 'center'])];
        $number = $receipt->result->payload->fiscal_receipt_number ?? null;
        $number = isset($number) ? ' '.static::trans('shared.#').$number : '';

        return $table->setRows([
            [new TableCell($receipt->payload->company->name ?? '-', $options)],
            [new TableCell($receipt->payload->company->payment_address ?? '-', $options)],
            [new TableCell(static::trans('receipt.company.inn').' '.($receipt->payload->company->inn ?? '-'), $options)],
            [new TableCell(static::trans('receipt.company.payment_site').': '
                .($receipt->payload->company->payment_site ?? '-'), $options)],
            [new TableCell('<comment>'.Str::upper(static::trans('receipt.title')).$number.'</comment>', $options)],
            new TableSeparator,
        ]);
    }

    protected static function setupReceiptTableHeader(Table $table, Receipt $receipt): Table
    {
        return $table->addRows([
            [$receipt->operation?->getDescription() ?? '-', $receipt->result?->payload->receipt_datetime->format('d.m.Y H:i') ?? '-'],
            [static::trans('result.shift_number'), $receipt->result?->payload->shift_number ?? '-'],
            [static::trans('receipt.company.tax_system'), $receipt->payload->company->tax_system?->getDescription('short') ?? '-'],
            [static::trans('receipt.client.phone_or_email'), $receipt->payload->client->email ?? $receipt->payload->client->phone],
            [static::trans('receipt.company.email'), $receipt->payload->company->email ?? '-'],
            [static::trans('result.device_code'), $receipt->result?->extra->device_code ?? '-'],
            [static::trans('result.online_attribute'), static::trans('shared.yes')],
            new TableSeparator,
        ]);
    }

    protected static function addReceiptTableItem(Table $table, Item $item): Table
    {
        $itemVatSum = $item->getVatSum();

        return $table->addRows([
            [new TableCell('<info>'.$item->name.'</info>', ['colspan' => 2])],
            ['', sprintf('%d x %.2f', $item->quantity, $item->price)],
            [static::trans('receipt.items.sum'), sprintf('%.2f', $item->sum)],
            [static::trans('receipt.items.vat.type'), $item->vat->type->getDescription('short')],
            $itemVatSum ? [static::trans('receipt.items.vat.sum'), sprintf('%.2f', $itemVatSum)] : [],
            [static::trans('receipt.items.payment_object'), $item->payment_object->getDescription()],
            [static::trans('receipt.items.payment_method'), $item->payment_method->getDescription()],
            new TableSeparator,
        ]);
    }

    protected static function setupReceiptTableTotal(Table $table, Receipt $receipt): Table
    {
        $vats = $receipt->payload->getVats();

        return $table->addRows([
            [static::trans('receipt.total'), sprintf('%.2f', $receipt->payload->total)],
            [static::trans('receipt.payments.cash'), sprintf('%.2f', $receipt->payload->payments->cash)],
            [static::trans('receipt.payments.electronic'), sprintf('%.2f', $receipt->payload->payments->electronic)],
            [static::trans('receipt.payments.prepaid'), sprintf('%.2f', $receipt->payload->payments->prepaid)],
            [static::trans('receipt.payments.postpaid'), sprintf('%.2f', $receipt->payload->payments->postpaid)],
            [static::trans('receipt.payments.other'), sprintf('%.2f', $receipt->payload->payments->other)],
            $vats->vat20 ? [static::trans('receipt.vats.vat20'), sprintf('%.2f', $vats->vat20)] : [],
            $vats->vat10 ? [static::trans('receipt.vats.vat10'), sprintf('%.2f', $vats->vat10)] : [],
            $vats->with_vat0 ? [static::trans('receipt.vats.with_vat0'), sprintf('%.2f', $vats->with_vat0)] : [],
            $vats->without_vat ? [static::trans('receipt.vats.without_vat'), sprintf('%.2f', $vats->without_vat)] : [],
            $vats->vat120 ? [static::trans('receipt.vats.vat120'), sprintf('%.2f', $vats->vat120)] : [],
            $vats->vat110 ? [static::trans('receipt.vats.vat110'), sprintf('%.2f', $vats->vat110)] : [],
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

    protected static function setupReceiptTableQRCode(Table $table, Receipt $receipt): Table
    {
        $table->addRow(new TableSeparator);

        $qrCode = ReceiptQRCode::for($receipt->result->payload, $receipt->operation)->block();
        foreach (explode("\n", $qrCode->getString()) as $qrCodeLine) {
            $qrCodeLine && $table->addRow([new TableCell($qrCodeLine, [
                'colspan' => 2,
                'style' => new TableCellStyle(['align' => 'center'])
            ])]);
        }

        return $table;
    }

    protected static function trans(string $key): string
    {
        return Lang::get('fiscal-registrar::main.'.$key);
    }
}
