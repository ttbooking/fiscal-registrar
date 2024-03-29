<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Database\Factories;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Eloquent\Factories\Factory;
use TTBooking\FiscalRegistrar\DTO;
use TTBooking\FiscalRegistrar\Enums\Operation;
use TTBooking\FiscalRegistrar\Enums\PaymentMethod;
use TTBooking\FiscalRegistrar\Enums\PaymentObject;
use TTBooking\FiscalRegistrar\Enums\VatType;
use TTBooking\FiscalRegistrar\Models\Receipt;

/**
 * @extends Factory<Receipt>
 */
class ReceiptFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Receipt>
     */
    protected $model = Receipt::class;

    protected Repository $config;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $connections = array_keys(array_filter(
            config('fiscal-registrar.connections'),
            fn ($connection) => $connection['test'] ?? false
        ));

        return [
            'state' => Receipt::STATE_CREATED,
            'connection' => $this->faker->randomElement($connections),
            'operation' => $this->faker->randomElement(Operation::cases()),
            'external_id' => $this->faker->uuid(),
            'payload' => fn () => new DTO\Receipt(

                client: new DTO\Receipt\Client(
                    email: config('fiscal-registrar.test_email') ?? $this->faker->safeEmail(),
                ),

                items: array_map(fn () => new DTO\Receipt\Item(
                    name: $this->faker->unique()->commodity(),
                    price: $price = $this->faker->numberBetween(1, 10000),
                    quantity: $quantity = $this->faker->numberBetween(1, 10),
                    sum: $price * $quantity,
                    measurement_unit: $this->faker->optional()->randomElement(['шт.', 'кг']),
                    payment_method: PaymentMethod::FullPrepayment,
                    payment_object: PaymentObject::Commodity,
                    vat: new DTO\Receipt\Item\Vat(type: VatType::VAT20),
                ), range(1, $this->faker->numberBetween(1, 10))),

            ),
        ];
    }

    /**
     * @return static
     */
    public function registered(): self
    {
        return $this->state(fn () => [
            'state' => Receipt::STATE_REGISTERED,
            'internal_id' => $this->faker->uuid(),
        ]);
    }

    /**
     * @return static
     */
    public function processed(): self
    {
        return $this->registered()->state(fn () => [
            'state' => Receipt::STATE_PROCESSED,
        ]);
    }

    /**
     * Get the name of the model that is generated by the factory.
     */
    public function modelName(): string
    {
        return config('fiscal-registrar.model', parent::modelName());
    }
}
