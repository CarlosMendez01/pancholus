<?php

namespace Database\Seeders;

use App\Models\PdiDetail;
use Illuminate\Database\Seeder;

class PdiDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PdiDetail::firstOrCreate(['PDI_id' => 1, 'meal_id' => 1], ['quantity' => 14]);
    }
}
