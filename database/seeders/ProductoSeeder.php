<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Producto::create([
            'nombre' => 'Hamburguesa Clásica',
            'descripcion' => 'Hamburguesa con carne, queso y lechuga',
            'precio' => 8500,
        ]);

        Producto::create([
            'nombre' => 'Papas Fritas',
            'descripcion' => 'Porción de papas fritas',
            'precio' => 4000,
        ]);

        Producto::create([
            'nombre' => 'Gaseosa',
            'descripcion' => 'Bebida de 500 ml',
            'precio' => 2500,
        ]);
    }
}
