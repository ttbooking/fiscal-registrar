<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TTBooking\FiscalRegistrar\Models\Receipt;

class ReceiptFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Receipt::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
        ];
    }
}
