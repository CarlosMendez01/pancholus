<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EstadoPedidoInsumo;

class EstadoPedidoInsumoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EstadoPedidoInsumo::create(['descripcion' => 'Pendiente']);
        EstadoPedidoInsumo::create(['descripcion' => 'Aprobado']);
        EstadoPedidoInsumo::create(['descripcion' => 'En preparación']);
        EstadoPedidoInsumo::create(['descripcion' => 'Entregado']);
        EstadoPedidoInsumo::create(['descripcion' => 'Cancelado']);
    }
}
