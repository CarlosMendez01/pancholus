<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Stock;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Stock::create([
            'cantidad_disponible' => 100,
            'stock_minimo' => 20,
            'producto_id' => 1,
            'deposito_id' => 1,
        ]);

        Stock::create([
            'cantidad_disponible' => 80,
            'stock_minimo' => 15,
            'producto_id' => 2,
            'deposito_id' => 1,
        ]);

        Stock::create([
            'cantidad_disponible' => 150,
            'stock_minimo' => 30,
            'producto_id' => 3,
            'deposito_id' => 2,
        ]);
    }
}
