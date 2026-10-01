<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PDI;

class PDISeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PDI::create([
            'food_truck_id' => 1,
            'status' => 'pendiente'
        ]);

        PDI::create([
            'food_truck_id' => 2,
            'status' => 'pendiente'
        ]);

        PDI::create([
            'food_truck_id' => 3,
            'status' => 'pendiente'
        ]);
    }
}
