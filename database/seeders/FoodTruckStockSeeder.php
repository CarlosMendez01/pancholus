<?php

namespace Database\Seeders;

use App\Models\FoodTruckStock;
use Illuminate\Database\Seeder;

class FoodTruckStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FoodTruckStock::firstOrCreate(['food_truck_id' => 1, 'ingredient_id' => 1], ['quantity' => 100]);
    }
}
