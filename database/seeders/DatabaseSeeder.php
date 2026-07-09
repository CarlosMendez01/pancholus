<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CountrySeeder::class,
            ProvinceSeeder::class,
            ProductoSeeder::class,
            DepositoSeeder::class,
            ClienteSeeder::class,
            EstadoFoodtruckSeeder::class,
            EstadoPedidoSeeder::class,
            EstadoPedidoInsumoSeeder::class,
            PuntoVentaSeeder::class,
            FoodtruckSeeder::class,
            StockSeeder::class,
            PedidoSeeder::class,
            DetallePedidoSeeder::class,
            PedidoInsumoSeeder::class,
        ]);
    }
}
