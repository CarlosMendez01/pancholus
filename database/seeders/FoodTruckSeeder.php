<?php

namespace Database\Seeders;

use App\Models\FoodTruck;
use Illuminate\Database\Seeder;

class FoodTruckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FoodTruck::firstOrCreate(['license_plate' => '000ABCD'], ['status' => 'habilitado']);
        FoodTruck::firstOrCreate(['license_plate' => '111EFGH'], ['status' => 'habilitado']);
        FoodTruck::firstOrCreate(['license_plate' => '222IJKL'], ['status' => 'habilitado']);
    }
}
