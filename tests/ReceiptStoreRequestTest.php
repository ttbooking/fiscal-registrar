<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Tests;

use Illuminate\Support\Facades\Validator;
use TTBooking\FiscalRegistrar\Http\Requests\ReceiptStoreRequest;

class ReceiptStoreRequestTest extends TestCase
{
    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    protected function payload(array $overrides = []): array
    {
        return [
            'payload' => array_replace_recursive([
                'client' => ['email' => 'client@example.com'],
                'company' => ['inn' => '1234567890'],
                'items' => [[
                    'name' => 'Product',
                    'price' => 100,
                    'quantity' => 1,
                    'sum' => 100,
                ]],
                'total' => 100,
            ], $overrides),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function validate(array $data): \Illuminate\Validation\Validator
    {
        return Validator::make($data, (new ReceiptStoreRequest)->rules());
    }

    public function test_valid_payload_passes(): void
    {
        $this->assertSame([], $this->validate($this->payload())->errors()->toArray());
    }

    public function test_item_supplier_info_is_required_with_item_agent_info(): void
    {
        $validator = $this->validate($this->payload([
            'items' => [[
                'agent_info' => ['type' => 'paying_agent'],
            ]],
        ]));

        $this->assertTrue($validator->errors()->has('payload.items.0.supplier_info'));
    }

    public function test_receipt_supplier_info_is_required_with_receipt_agent_info(): void
    {
        $validator = $this->validate($this->payload([
            'agent_info' => ['type' => 'paying_agent'],
        ]));

        $this->assertTrue($validator->errors()->has('payload.supplier_info'));
    }

    public function test_supplier_info_is_optional_without_agent_info(): void
    {
        $validator = $this->validate($this->payload());

        $this->assertFalse($validator->errors()->has('payload.supplier_info'));
        $this->assertFalse($validator->errors()->has('payload.items.0.supplier_info'));
    }

    public function test_vat_type_is_required_only_when_vat_is_present(): void
    {
        $this->assertTrue($this->validate($this->payload([
            'items' => [['vat' => ['sum' => 20]]],
        ]))->errors()->has('payload.items.0.vat.type'));

        $this->assertTrue($this->validate($this->payload([
            'items' => [['vat' => ['type' => 'vat22']]],
        ]))->passes());
    }
}
