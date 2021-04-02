<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Database\Seeders;

use Illuminate\Database\Seeder;
use TTBooking\FiscalRegistrar\Models\Receipt;

class ReceiptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Receipt::factory()->count(100)->create();
    }
}
