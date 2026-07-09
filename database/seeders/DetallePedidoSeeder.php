<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DetallePedido;

class DetallePedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DetallePedido::create([
            'cantidad' => 2,
            'precio_unitario' => 8500,
            'subtotal' => 17000,
            'pedido_id' => 1,
            'producto_id' => 1,
        ]);

        DetallePedido::create([
            'cantidad' => 1,
            'precio_unitario' => 4000,
            'subtotal' => 4000,
            'pedido_id' => 2,
            'producto_id' => 2,
        ]);

        DetallePedido::create([
            'cantidad' => 3,
            'precio_unitario' => 2500,
            'subtotal' => 7500,
            'pedido_id' => 3,
            'producto_id' => 3,
        ]);
    }
}
