<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PedidoInsumo;

class PedidoInsumoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PedidoInsumo::create([
            'fecha' => '2026-07-01',
            'estado_pedido_insumo_id' => '1',
            'observacion' => 'Solicitar reposición de pan y salchichas.',
            'foodtruck_id' => 1,
            'user_id' => 1,
        ]);

        PedidoInsumo::create([
            'fecha' => '2026-07-02',
            'estado_pedido_insumo_id' => '2',
            'observacion' => 'Reposición de bebidas.',
            'foodtruck_id' => 2,
            'user_id' => 2,
        ]);

        PedidoInsumo::create([
            'fecha' => '2026-07-03',
            'estado_pedido_insumo_id' => '3',
            'observacion' => 'Reposición completa de insumos.',
            'foodtruck_id' => 1,
            'user_id' => 1,
        ]);
    }
}
