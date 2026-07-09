<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PuntoVenta;

class PuntoVentaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PuntoVenta::create([
    'nombre' => 'Plaza 9 de Julio',
    'direccion' => 'Centro',
    'horario' => '08:00 - 22:00',
]);

PuntoVenta::create([
    'nombre' => 'Costanera',
    'direccion' => 'Av. Costanera',
    'horario' => '18:00 - 02:00',
]);

PuntoVenta::create([
    'nombre' => 'Terminal',
    'direccion' => 'Av. Quaranta',
    'horario' => '06:00 - 23:00',
]);
    }
}
