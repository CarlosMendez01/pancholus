<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Deposito;

class DepositoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Deposito::create([
            'nombre' => 'Depósito Central',
            'ubicacion' => 'Av. San Martín 1234',
        ]);

        Deposito::create([
            'nombre' => 'Depósito Norte',
            'ubicacion' => 'Ruta Nacional 12 Km 8',
        ]);
    }
}
