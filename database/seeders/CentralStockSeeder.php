<?php

namespace Database\Seeders;

use App\Models\CentralStock;
use Illuminate\Database\Seeder;

class CentralStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CentralStock::firstOrCreate(['product_id' => 1], ['quantity' => 500]);
    }
}
