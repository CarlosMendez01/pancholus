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
        $foodTrucks = [
            ['license_plate' => '000ABCD', 'status' => 'habilitado'],
            ['license_plate' => '111EFGH', 'status' => 'habilitado'],
            ['license_plate' => '222IJKL', 'status' => 'habilitado'],
            ['license_plate' => '333MNOP', 'status' => 'habilitado'],
            ['license_plate' => '444QRST', 'status' => 'habilitado'],
            ['license_plate' => '555UVWX', 'status' => 'habilitado'],
            ['license_plate' => '666YZAB', 'status' => 'inhabilitado'],
            ['license_plate' => '777CDEF', 'status' => 'habilitado'],
            ['license_plate' => '888GHIJ', 'status' => 'inhabilitado'],
            ['license_plate' => '999KLMN', 'status' => 'habilitado'],
        ];

        foreach ($foodTrucks as $foodTruck) {
            FoodTruck::updateOrCreate(
                ['license_plate' => $foodTruck['license_plate']],
                $foodTruck,
            );
        }
    }
}
