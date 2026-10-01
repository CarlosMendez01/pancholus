<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FoodTruck;

class FoodTruckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FoodTruck::create([
            'license_plate' => '000ABCD',
            'status' => 'habilitado'
        ]);

        FoodTruck::create([
            'license_plate' => '111EFGH',
            'status' => 'habilitado'
        ]);

        FoodTruck::create([
            'license_plate' => '222IJKL',
            'status' => 'habilitado'
        ]);
    }
}
