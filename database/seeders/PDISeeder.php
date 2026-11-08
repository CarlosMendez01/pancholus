<?php

namespace Database\Seeders;

use App\Models\FoodTruck;
use App\Models\PDI;
use Illuminate\Database\Seeder;

class PDISeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['pendiente', 'realizado', 'cancelado', 'pendiente', 'realizado'];

        foreach (FoodTruck::orderBy('id')->get() as $index => $foodTruck) {
            PDI::firstOrCreate([
                'food_truck_id' => $foodTruck->id,
                'status' => $statuses[$index % count($statuses)],
            ]);
        }
    }
}
