<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FoodTruckAssignment;

class FoodTruckAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FoodTruckAssignment::create([
            'food_truck_id' => 1,
            'user_id' => 6
        ]);
    }
}
