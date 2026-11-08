<?php

namespace Database\Seeders;

use App\Models\FoodTruck;
use App\Models\FoodTruckStock;
use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class FoodTruckStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $foodTrucks = FoodTruck::orderBy('id')->get();
        $ingredients = Ingredient::orderBy('id')->take(10)->get();

        foreach ($foodTrucks as $truckIndex => $foodTruck) {
            foreach ($ingredients as $ingredientIndex => $ingredient) {
                FoodTruckStock::updateOrCreate(
                    [
                        'food_truck_id' => $foodTruck->id,
                        'ingredient_id' => $ingredient->id,
                    ],
                    ['quantity' => (($truckIndex + $ingredientIndex) % 10 + 1) * 10],
                );
            }
        }
    }
}
