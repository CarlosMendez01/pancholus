<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FoodTruckStock;

class FoodTruckStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FoodTruckStock::create([
            'food_truck_id' => 1,
            'ingredient_id' => 1,
            'quantity' => 100
        ]);
    }
}
