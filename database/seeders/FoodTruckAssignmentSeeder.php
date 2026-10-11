<?php

namespace Database\Seeders;

use App\Models\FoodTruckAssignment;
use Illuminate\Database\Seeder;

class FoodTruckAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FoodTruckAssignment::firstOrCreate(['food_truck_id' => 1, 'user_id' => 6]);
    }
}
