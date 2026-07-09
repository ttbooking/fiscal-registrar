<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Tests;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use TTBooking\FiscalRegistrar\DTO;
use TTBooking\FiscalRegistrar\Enums\PaymentMethod;
use TTBooking\FiscalRegistrar\Enums\PaymentObject;
use TTBooking\FiscalRegistrar\Enums\TaxSystem;
use TTBooking\FiscalRegistrar\Enums\VatType;
use TTBooking\FiscalRegistrar\Models\Receipt;

class DTOTest extends TestCase
{
    public function test_receipt_creation_from_array(): void
    {
        $receipt = DTO\Receipt::from([
            'client' => ['email' => 'client@example.com'],
            'items' => [
                [
                    'name' => 'Product A',
                    'price' => '100.004',
                    'quantity' => 20,
                    'payment_method' => 'full_prepayment',
                    'payment_object' => 'commodity',
                    'vat' => ['type' => 'vat22'],
                ],
                [
                    'name' => 'Product B',
                    'price' => 50,
                    'sum' => 50,
                    'vat' => ['type' => VatType::VAT10],
                ],
            ],
        ]);

        $this->assertSame('client@example.com', $receipt->client->email);

        // Company derived as empty object, payments and total derived from items
        $this->assertInstanceOf(DTO\Receipt\Company::class, $receipt->company);
        $this->assertNull($receipt->company->inn);
        $this->assertSame(2050.08, $receipt->total);
        $this->assertSame(2050.08, $receipt->payments->electronic);
        $this->assertSame(0.0, $receipt->payments->cash);

        // Items cast to a collection of Item DTOs
        $this->assertInstanceOf(Collection::class, $receipt->items);
        $this->assertContainsOnlyInstancesOf(DTO\Receipt\Item::class, $receipt->items);

        /** @var DTO\Receipt\Item $item */
        $item = $receipt->items->first();

        // Rounding cast applied, sum derived from price * quantity
        $this->assertSame(100.0, $item->price);
        $this->assertSame(2000.08, $item->sum);

        // Enums cast from raw values and enum instances
        $this->assertSame(PaymentMethod::FullPrepayment, $item->payment_method);
        $this->assertSame(PaymentObject::Commodity, $item->payment_object);
        $this->assertSame(VatType::VAT22, $item->vat?->type);
        $this->assertSame(VatType::VAT10, $receipt->items->last()->vat?->type);
    }

    public function test_item_defaults_applied_for_null_values(): void
    {
        $item = DTO\Receipt\Item::from([
            'name' => 'Product',
            'price' => 10,
            'quantity' => 3,
            'payment_method' => null,
            'payment_object' => null,
        ]);

        $this->assertSame(30.0, $item->sum);
        $this->assertSame(PaymentMethod::FullPrepayment, $item->payment_method);
        $this->assertSame(PaymentObject::Commodity, $item->payment_object);
    }

    public function test_receipt_creation_via_constructor(): void
    {
        $receipt = new DTO\Receipt(
            client: new DTO\Receipt\Client(email: 'client@example.com'),
            items: [
                new DTO\Receipt\Item(name: 'Product A', price: 100.004, quantity: 20),
                new DTO\Receipt\Item(name: 'Product B', price: 50, sum: 50),
            ],
        );

        // Same derivation as with from(): company empty, sum, total and payments computed
        $this->assertInstanceOf(DTO\Receipt\Company::class, $receipt->company);
        $this->assertInstanceOf(Collection::class, $receipt->items);
        $this->assertSame(2000.08, $receipt->items->first()?->sum);
        $this->assertSame(2050.08, $receipt->total);
        $this->assertSame(2050.08, $receipt->payments->electronic);
        $this->assertSame(0, $receipt->payments->cash);
    }

    public function test_receipt_constructor_respects_explicit_values(): void
    {
        $receipt = new DTO\Receipt(
            client: new DTO\Receipt\Client(email: 'client@example.com'),
            items: collect([new DTO\Receipt\Item(name: 'Product', price: 100)]),
            payments: new DTO\Receipt\Payments(cash: 100),
            total: 100,
        );

        $this->assertSame(100.0, $receipt->items->first()?->sum);
        $this->assertSame(100.0, $receipt->total);
        $this->assertSame(100, $receipt->payments->cash);
        $this->assertSame(0, $receipt->payments->electronic);
    }

    public function test_receipt_serialization(): void
    {
        $receipt = DTO\Receipt::from([
            'client' => ['email' => 'client@example.com'],
            'company' => ['tax_system' => TaxSystem::OSN],
            'items' => [['name' => 'Product', 'price' => 100, 'vat' => ['type' => 'vat22']]],
        ]);

        $array = $receipt->toArray();

        $this->assertSame('vat22', $array['items'][0]['vat']['type']);
        $this->assertSame(TaxSystem::OSN->value, $array['company']['tax_system']);
        $this->assertSame(100.0, $array['total']);

        $decoded = json_decode((string) $receipt, true);
        $this->assertEquals($array['payments'], $decoded['payments']);

        // Payments serialize in FFD payment type order (cash, electronic, prepaid, postpaid, other)
        $this->assertSame(
            ['cash', 'electronic', 'prepaid', 'postpaid', 'other'],
            array_keys($array['payments'])
        );
    }

    public function test_result_creation_and_timestamp_casting(): void
    {
        $result = DTO\Result::from([
            'internal_id' => 'abc-123',
            'timestamp' => '2026-01-15 12:30:45',
            'status' => 'done',
            'payload' => [
                'fiscal_receipt_number' => 1,
                'shift_number' => 2,
                'receipt_datetime' => '15.01.2026 12:30:45',
                'total' => '100.005',
                'fn_number' => '999',
                'ecr_registration_number' => '888',
                'fiscal_document_number' => 3,
                'fiscal_document_attribute' => 4,
            ],
            'extra' => ['group_code' => 'group'],
        ]);

        $this->assertInstanceOf(Carbon::class, $result->timestamp);
        $this->assertSame('2026-01-15 12:30:45', $result->timestamp->format('Y-m-d H:i:s'));
        $this->assertInstanceOf(Carbon::class, $result->payload?->receipt_datetime);
        $this->assertSame(100.0, $result->payload->total);
        $this->assertSame('www.nalog.ru', $result->payload->fns_site);
        $this->assertSame('group', $result->extra?->group_code);

        $decoded = json_decode($result->toJson(), true);
        $this->assertSame('abc-123', $decoded['internal_id']);
        $this->assertSame('group', $decoded['extra']['group_code']);
    }

    public function test_eloquent_cast_round_trip(): void
    {
        $model = new Receipt;
        $model->payload = DTO\Receipt::from([
            'client' => ['email' => 'client@example.com'],
            'items' => [['name' => 'Product', 'price' => 100, 'vat' => ['type' => 'vat22']]],
        ]);

        $raw = $model->getAttributes()['payload'];
        $this->assertJson($raw);

        $rehydrated = (new Receipt)
            ->setRawAttributes(['payload' => $raw])
            ->payload;

        $this->assertInstanceOf(DTO\Receipt::class, $rehydrated);
        $this->assertSame('client@example.com', $rehydrated->client->email);
        $this->assertSame(100.0, $rehydrated->total);
        $this->assertSame(VatType::VAT22, $rehydrated->items->first()?->vat?->type);
    }

    public function test_get_vats_calculation(): void
    {
        $receipt = DTO\Receipt::from([
            'client' => ['email' => 'client@example.com'],
            'items' => [
                ['name' => 'A', 'price' => 122, 'vat' => ['type' => 'vat22']],
                ['name' => 'B', 'price' => 100, 'vat' => ['type' => 'none']],
            ],
        ]);

        $vats = $receipt->getVats();

        $this->assertSame(22.0, round($vats->vat22, 2));
        $this->assertSame(100.0, round($vats->without_vat, 2));
    }
}
