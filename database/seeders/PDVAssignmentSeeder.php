<?php

namespace Database\Seeders;

use App\Models\FoodTruck;
use App\Models\PDV;
use App\Models\PDV_assignment;
use Illuminate\Database\Seeder;

class PDVAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pointsOfSale = PDV::orderBy('id')->get();
        $foodTrucks = FoodTruck::orderBy('id')->get();

        if ($foodTrucks->isEmpty()) {
            return;
        }

        foreach ($pointsOfSale as $index => $pointOfSale) {
            PDV_assignment::firstOrCreate([
                'PDV_id' => $pointOfSale->id,
                'food_truck_id' => $foodTrucks[$index % $foodTrucks->count()]->id,
            ]);
        }
    }
}
