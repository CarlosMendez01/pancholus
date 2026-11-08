<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Los primeros cuatro roles conservan su id histórico (1 = cliente,
     * 2 = administrador, 3 = empleado, 4 = encargado_deposito) porque el
     * middleware y los controles de acceso usan esos ids.
     */
    public function run(): void
    {
        $roles = [
            'cliente',
            'administrador',
            'empleado',
            'encargado_deposito',
            'cajero',
            'cocinero',
            'repartidor',
            'contador',
            'jefe_turno',
            'gerente_general',
        ];

        foreach ($roles as $role) {
            Rol::firstOrCreate(['role' => $role]);
        }
    }
}
