<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rol::firstOrCreate(['role' => 'cliente']);
        Rol::firstOrCreate(['role' => 'administrador']);
        Rol::firstOrCreate(['role' => 'empleado']);
        Rol::firstOrCreate(['role' => 'encargado_deposito']);
    }
}
