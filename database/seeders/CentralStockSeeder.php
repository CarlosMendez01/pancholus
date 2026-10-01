<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CentralStock;

class CentralStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CentralStock::create([
            'product_id' => 1,
            'quantity' => 500
        ]);
    }
}
