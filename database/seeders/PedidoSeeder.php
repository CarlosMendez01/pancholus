<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pedido;

class PedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pedido::create([
            'fecha' => '2026-07-01',
            'hora' => '12:30:00',
            'total' => 12500,
            'estado_pedido_id' => 1,
            'cliente_id' => 1,
            'user_id' => 1,
        ]);

        Pedido::create([
            'fecha' => '2026-07-01',
            'hora' => '13:15:00',
            'total' => 17000,
            'estado_pedido_id' => 2,
            'cliente_id' => 2,
            'user_id' => 1,
        ]);

        Pedido::create([
            'fecha' => '2026-07-01',
            'hora' => '14:00:00',
            'total' => 8500,
            'estado_pedido_id' => 3,
            'cliente_id' => 3,
            'user_id' => 2,
        ]);
    }
}
