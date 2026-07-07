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
