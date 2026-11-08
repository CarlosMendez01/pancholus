<?php

namespace Database\Seeders;

use App\Models\PDV;
use Illuminate\Database\Seeder;

class PDVSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Las coordenadas tienen dos decimales porque la migración define las
     * columnas como `decimal(8, 2)`; así `firstOrCreate` sigue siendo
     * idempotente al volver a ejecutar las semillas.
     */
    public function run(): void
    {
        $pointsOfSale = [
            ['latitude' => -27.37, 'longitude' => -55.90],
            ['latitude' => -27.36, 'longitude' => -55.89],
            ['latitude' => -27.35, 'longitude' => -55.88],
            ['latitude' => -27.34, 'longitude' => -55.87],
            ['latitude' => -27.33, 'longitude' => -55.86],
            ['latitude' => -27.32, 'longitude' => -55.85],
            ['latitude' => -27.31, 'longitude' => -55.84],
            ['latitude' => -27.30, 'longitude' => -55.83],
            ['latitude' => -27.29, 'longitude' => -55.82],
            ['latitude' => -27.28, 'longitude' => -55.81],
        ];

        foreach ($pointsOfSale as $pointOfSale) {
            PDV::firstOrCreate($pointOfSale);
        }
    }
}
