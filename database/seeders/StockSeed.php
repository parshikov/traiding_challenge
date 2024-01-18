<?php

namespace Database\Seeders;

use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StockSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Single row
        Stock::factory()->create([
            'name' => 'USD:CHF',
            'price' => 100,
        ]);

        // Multiple rows
        $name = 'USD:GBP';

        Carbon::setTestNow(Carbon::create(2024, 1, 18, 14, 45, 56));
        Stock::factory()->create([
            'name' => $name,
            'price' => 94920,
        ]);

        Carbon::setTestNow(Carbon::create(2024, 1, 18, 14, 45, 58));
        Stock::factory()->create([
            'name' => $name,
            'price' => 19292,
        ]);
        Carbon::setTestNow();
    }
}
