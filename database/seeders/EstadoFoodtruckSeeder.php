<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EstadoFoodtruck;

class EstadoFoodtruckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EstadoFoodtruck::create(['descripcion' => 'Disponible']);
        EstadoFoodtruck::create(['descripcion' => 'En servicio']);
        EstadoFoodtruck::create(['descripcion' => 'Mantenimiento']);
        EstadoFoodtruck::create(['descripcion' => 'Fuera de servicio']);
    }
}
