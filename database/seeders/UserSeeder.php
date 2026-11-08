<?php

namespace Database\Seeders;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Rol::pluck('id', 'role');

        $users = [
            ['name' => 'alejo', 'last_name' => 'acosta', 'dni' => '43.702.748', 'phone' => null, 'email' => 'alejo@gmail.com', 'password' => '1234', 'role' => 'cliente'],
            ['name' => 'carlos', 'last_name' => 'mendez', 'dni' => '43.528.476', 'phone' => null, 'email' => 'carlos@gmail.com', 'password' => '5678', 'role' => 'cliente'],
            ['name' => 'ezequiel', 'last_name' => 'vallejos', 'dni' => '46.832.888', 'phone' => null, 'email' => 'ezequiel@gmail.com', 'password' => '91011', 'role' => 'cliente'],
            ['name' => 'facundo', 'last_name' => 'billalba', 'dni' => '47.533.592', 'phone' => '3764-198151', 'email' => 'facu@gmail.com', 'password' => '12131', 'role' => 'cliente'],
            ['name' => 'carlos', 'last_name' => 'villalba', 'dni' => '11.111.111', 'phone' => null, 'email' => 'villalba@gmail.com', 'password' => '12131', 'role' => 'encargado_deposito'],
            ['name' => 'pepe', 'last_name' => null, 'dni' => '22.222.222', 'phone' => null, 'email' => 'elpepe@gmail.com', 'password' => '12131', 'role' => 'administrador'],
            ['name' => 'juan', 'last_name' => 'perez', 'dni' => '33.333.333', 'phone' => null, 'email' => 'empleado@gmail.com', 'password' => '1234', 'role' => 'empleado'],
            ['name' => 'maria', 'last_name' => 'gomez', 'dni' => '44.444.444', 'phone' => '3764-200001', 'email' => 'maria.gomez@gmail.com', 'password' => '1234', 'role' => 'cliente'],
            ['name' => 'lucia', 'last_name' => 'fernandez', 'dni' => '45.555.555', 'phone' => '3764-200002', 'email' => 'lucia.fernandez@gmail.com', 'password' => '1234', 'role' => 'cliente'],
            ['name' => 'pedro', 'last_name' => 'ramirez', 'dni' => '46.666.666', 'phone' => '3764-200003', 'email' => 'pedro.ramirez@gmail.com', 'password' => '1234', 'role' => 'empleado'],
            ['name' => 'ana', 'last_name' => 'torres', 'dni' => '47.777.777', 'phone' => '3764-200004', 'email' => 'ana.torres@gmail.com', 'password' => '1234', 'role' => 'empleado'],
            ['name' => 'jorge', 'last_name' => 'silva', 'dni' => '48.888.888', 'phone' => '3764-200005', 'email' => 'jorge.silva@gmail.com', 'password' => '1234', 'role' => 'encargado_deposito'],
            ['name' => 'sofia', 'last_name' => 'rojas', 'dni' => '49.999.999', 'phone' => '3764-200006', 'email' => 'sofia.rojas@gmail.com', 'password' => '1234', 'role' => 'cliente'],
            ['name' => 'diego', 'last_name' => 'herrera', 'dni' => '50.000.000', 'phone' => '3764-200007', 'email' => 'diego.herrera@gmail.com', 'password' => '1234', 'role' => 'administrador'],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'last_name' => $user['last_name'],
                    'dni' => $user['dni'],
                    'phone' => $user['phone'],
                    'password' => $user['password'],
                    'role_id' => $roles[$user['role']],
                ],
            );
        }
    }
}
