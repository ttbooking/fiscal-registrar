<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Tests\Drivers;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\Routing\UrlGenerator;
use Lamoda\AtolClient\V4\AtolApi;
use Lamoda\AtolClient\V4\DTO\Register as AtolRegister;
use TTBooking\FiscalRegistrar\Drivers\AtolApiFactory;
use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\Enums\Operation;
use TTBooking\FiscalRegistrar\Exceptions\DriverException;
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

    /**
     * @param  list<array<string, mixed>>  $responses
     */
    protected function makeDriverWithResponses(array $responses): TestAtolDriver
    {
        $mock = new MockHandler(array_map(
            static fn (array $response) => new Response(200, [], json_encode($response)),
            $responses
        ));

        $api = new AtolApi(
            (new AtolApiFactory)->getConverter(),
            new Client(['handler' => HandlerStack::create($mock)]),
            [],
            'https://testonline.atol.ru/possystem'
        );

        return $this->makeDriver()->setApi($api);
    }

    /**
     * @return array<string, mixed>
     */
    protected static function tokenResponse(string $token): array
    {
        return ['timestamp' => '15.01.2026 12:30:45', 'token' => $token];
    }

    /**
     * @return array<string, mixed>
     */
    protected static function expiredTokenResponse(): array
    {
        return [
            'timestamp' => '15.01.2026 12:30:45',
            'status' => 'fail',
            'error' => ['error_id' => 'e1', 'code' => 11, 'text' => 'Token expired', 'type' => 'system'],
        ];
    }

    public function test_register_retries_once_with_fresh_token(): void
    {
        $driver = $this->makeDriverWithResponses([
            static::tokenResponse('stale-token'),
            static::expiredTokenResponse(),
            static::tokenResponse('fresh-token'),
            ['timestamp' => '15.01.2026 12:30:45', 'status' => 'wait', 'uuid' => 'uuid-1'],
        ]);

        $receipt = Receipt::from([
            'client' => ['email' => 'client@example.com'],
            'items' => [['name' => 'Product', 'price' => 100, 'vat' => ['type' => 'vat22']]],
        ]);

        $this->assertSame('uuid-1', $driver->register(Operation::Sell, 'external-1', $receipt));
    }

    public function test_register_gives_up_after_one_token_refresh(): void
    {
        $driver = $this->makeDriverWithResponses([
            static::tokenResponse('stale-token'),
            static::expiredTokenResponse(),
            static::tokenResponse('fresh-token'),
            static::expiredTokenResponse(),
        ]);

        $receipt = Receipt::from([
            'client' => ['email' => 'client@example.com'],
            'items' => [['name' => 'Product', 'price' => 100, 'vat' => ['type' => 'vat22']]],
        ]);

        try {
            $driver->register(Operation::Sell, 'external-1', $receipt);
            $this->fail('DriverException was not thrown.');
        } catch (DriverException $e) {
            // Code 11 proves the loop stopped on the second expired-token
            // response instead of draining the (empty) response queue.
            $this->assertSame(11, $e->getCode());
            $this->assertSame('Token expired', $e->getMessage());
        }
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
