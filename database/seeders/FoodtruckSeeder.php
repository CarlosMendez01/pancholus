<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Foodtruck;

class FoodtruckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Foodtruck::create([
            'nombre' => 'Foodtruck 1',
            'patente' => 'AE123BC',
            'estado_foodtruck_id' => 1,
            'punto_venta_id' => 1,
        ]);

        Foodtruck::create([
            'nombre' => 'Foodtruck 2',
            'patente' => 'AF456CD',
            'estado_foodtruck_id' => 2,
            'punto_venta_id' => 2,
        ]);

        Foodtruck::create([
            'nombre' => 'Foodtruck 3',
            'patente' => 'AG789EF',
            'estado_foodtruck_id' => 3,
            'punto_venta_id' => 3,
        ]);
    }
}
