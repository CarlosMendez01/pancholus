<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EstadoPedido;

class EstadoPedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EstadoPedido::create(['descripcion' => 'Pendiente']);
        EstadoPedido::create(['descripcion' => 'En preparación']);
        EstadoPedido::create(['descripcion' => 'Entregado']);
        EstadoPedido::create(['descripcion' => 'Cancelado']);
    }
}
