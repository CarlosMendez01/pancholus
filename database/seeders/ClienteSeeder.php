<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cliente::create([
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'telefono' => '3764123456',
        ]);

        Cliente::create([
            'nombre' => 'María',
            'apellido' => 'Gómez',
            'telefono' => '3764556789',
        ]);

        Cliente::create([
            'nombre' => 'Carlos',
            'apellido' => 'López',
            'telefono' => '3764987654',
        ]);
    }
}
