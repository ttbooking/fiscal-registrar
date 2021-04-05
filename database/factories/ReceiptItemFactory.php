<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TTBooking\FiscalRegistrar\DTO\Receipt\Item;
use TTBooking\FiscalRegistrar\Enums\VATType;

class ReceiptItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->productName,
            'price' => $this->faker->numberBetween(1, 10000),
            'quantity' => $this->faker->numberBetween(1, 10),
            'measurementUnit' => $this->faker->optional()->randomElement(['шт', 'кг']),
            'vat' => Item\VAT::new(VATType::VAT20()),
        ];
    }
}
