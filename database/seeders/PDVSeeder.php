<?php

namespace Database\Seeders;

use App\Models\PDV;
use Illuminate\Database\Seeder;

class PDVSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PDV::firstOrCreate([
            'latitude' => -27.36708,
            'longitude' => -55.89608,
        ]);

        PDV::firstOrCreate([
            'latitude' => -82.25801,
            'longitude' => 15.82636,
        ]);
    }
}
