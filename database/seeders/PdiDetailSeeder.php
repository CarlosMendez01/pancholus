<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PdiDetail;

class PdiDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PdiDetail::create([
            'PDI_id' => 1,
            'meal_id' => 1,
            'quantity' => 14
        ]);
    }
}
