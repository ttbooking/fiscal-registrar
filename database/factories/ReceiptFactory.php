<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Database\Factories;

use Illuminate\Container\Container;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Eloquent\Factories\Factory;
use TTBooking\FiscalRegistrar\DTO;
use TTBooking\FiscalRegistrar\Enums\Operation;
use TTBooking\FiscalRegistrar\Enums\PaymentMethod;
use TTBooking\FiscalRegistrar\Enums\PaymentObject;
use TTBooking\FiscalRegistrar\Enums\VATType;
use TTBooking\FiscalRegistrar\Models\Receipt;

class ReceiptFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Receipt::class;

    protected Repository $config;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'connection' => 'atol',
            'operation' => $this->faker->optional()->randomElement(Operation::values()),
            'external_id' => $this->faker->uuid,
            'internal_id' => $this->faker->uuid,
            'data' => DTO\Receipt::new(

                DTO\Receipt\Client::new(config('fiscal-registrar.test_email', $this->faker->safeEmail)),

                null, null, null,

                [
                    'name' => $this->faker->unique()->productName,
                    'price' => $price = $this->faker->numberBetween(1, 10000),
                    'quantity' => $quantity = $this->faker->numberBetween(1, 10),
                    'sum' => $price * $quantity,
                    'measurementUnit' => $this->faker->optional()->randomElement(['шт.', 'кг']),
                    'paymentMethod' => PaymentMethod::FullPrepayment(),
                    'paymentObject' => PaymentObject::Commodity(),
                    'vat' => DTO\Receipt\Item\VAT::new(VATType::VAT20()),
                ],

            ),
        ];
    }

    /**
     * Get the name of the model that is generated by the factory.
     *
     * @return string
     */
    public function modelName(): string
    {
        return config('fiscal-registrar.model', parent::modelName());
    }
}
