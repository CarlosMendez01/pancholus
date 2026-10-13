<?php

namespace Database\Seeders;

use App\Models\PDI;
use Illuminate\Database\Seeder;

class PDISeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PDI::firstOrCreate(['food_truck_id' => 1, 'status' => 'pendiente']);
        PDI::firstOrCreate(['food_truck_id' => 2, 'status' => 'pendiente']);
        PDI::firstOrCreate(['food_truck_id' => 3, 'status' => 'pendiente']);
    }
}
