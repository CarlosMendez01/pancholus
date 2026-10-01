<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PDV;

class PDVSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PDV::create([
            'latitude' => -27.36708,
            'longitude' => -55.89608
        ]);

        PDV::create([
            'latitude' => -82.25801,
            'longitude' => 15.82636
        ]);
    }
}
