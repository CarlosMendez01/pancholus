<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Country::create(['nombre' => 'Argentina']);
        Country::create(['nombre' => 'Paraguay']);
        Country::create(['nombre' => 'Brasil']);
        Country::create(['nombre' => 'Chile']);
        Country::create(['nombre' => 'Uruguay']);
    }
}
