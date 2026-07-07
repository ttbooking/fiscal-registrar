<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Tests\Drivers;

use Illuminate\Contracts\Routing\UrlGenerator;
use Lamoda\AtolClient\V4\DTO\Register as AtolRegister;
use TTBooking\FiscalRegistrar\Drivers\AtolApiFactory;
use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\Tests\TestCase;

class AtolDriverTest extends TestCase
{
    protected function makeDriver(): TestAtolDriver
    {
        return new TestAtolDriver(
            new AtolApiFactory,
            $this->app['cache.store'],
            $this->app->make(UrlGenerator::class),
            $this->app['config']['fiscal-registrar.connections.atol'],
            'atol'
        );
    }

    public function test_item_without_vat_is_registered_with_vat_type_none(): void
    {
        $receipt = Receipt::from([
            'client' => ['email' => 'client@example.com'],
            'items' => [['name' => 'Product', 'price' => 100]],
        ]);

        $request = $this->makeDriver()->exposedMakeRequest('external-1', $receipt);

        $vat = $request->receipt->items[0]->vat;
        $this->assertSame(AtolRegister\VatType::None, $vat->type);
        $this->assertSame(0.0, $vat->sum);
    }

    public function test_item_optional_fields_are_mapped(): void
    {
        $receipt = Receipt::from([
            'client' => ['email' => 'client@example.com'],
            'items' => [[
                'name' => 'Product',
                'price' => 100,
                'measurement_unit' => 'кг',
                'payment_object' => 'service',
                'user_data' => 'extra',
                'agent_info' => [
                    'type' => 'paying_agent',
                    'paying_agent' => ['operation' => 'top-up', 'phones' => ['+79170000000']],
                ],
                'supplier_info' => ['phones' => ['+79160000000'], 'name' => 'ООО Поставщик', 'inn' => '3358994128'],
            ]],
        ]);

        $item = $this->makeDriver()->exposedMakeRequest('external-1', $receipt)->receipt->items[0];

        $this->assertSame('кг', $item->measurementUnit);
        $this->assertSame(AtolRegister\PaymentObject::Service, $item->paymentObject);
        $this->assertSame('extra', $item->userData);
        $this->assertSame(AtolRegister\AgentType::PayingAgent, $item->agentInfo?->type);
        $this->assertSame('top-up', $item->agentInfo->payingAgent?->operation);
        $this->assertSame(['+79170000000'], $item->agentInfo->payingAgent->phones);
        $this->assertSame('ООО Поставщик', $item->supplierInfo?->name);
        $this->assertSame('3358994128', $item->supplierInfo->inn);
        $this->assertSame(['+79160000000'], $item->supplierInfo->phones);
    }

    public function test_receipt_vats_are_mapped(): void
    {
        $receipt = Receipt::from([
            'client' => ['email' => 'client@example.com'],
            'items' => [['name' => 'Product', 'price' => 122, 'vat' => ['type' => 'vat22']]],
            'vats' => ['vat22' => 22, 'without_vat' => 100],
        ]);

        $vats = $this->makeDriver()->exposedMakeRequest('external-1', $receipt)->receipt->vats;

        $this->assertCount(2, $vats);
        $this->assertSame(AtolRegister\VatType::Vat22, $vats[0]->type);
        $this->assertSame(22.0, $vats[0]->sum);
        $this->assertSame(AtolRegister\VatType::None, $vats[1]->type);
        $this->assertSame(100.0, $vats[1]->sum);
    }

    public function test_receipt_without_vats_maps_to_null(): void
    {
        $receipt = Receipt::from([
            'client' => ['email' => 'client@example.com'],
            'items' => [['name' => 'Product', 'price' => 100]],
        ]);

        $this->assertNull($this->makeDriver()->exposedMakeRequest('external-1', $receipt)->receipt->vats);
    }

    public function test_item_vat_is_mapped(): void
    {
        $receipt = Receipt::from([
            'client' => ['email' => 'client@example.com'],
            'items' => [['name' => 'Product', 'price' => 122, 'vat' => ['type' => 'vat22']]],
        ]);

        $request = $this->makeDriver()->exposedMakeRequest('external-1', $receipt);

        $vat = $request->receipt->items[0]->vat;
        $this->assertSame(AtolRegister\VatType::Vat22, $vat->type);
        $this->assertSame(22.0, $vat->sum);
    }
}
