<?php

namespace Database\Seeders;

use App\Models\Meal;
use Illuminate\Database\Seeder;

class MealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Meal::firstOrCreate(['name' => 'superpancho'], ['price' => 2500]);
        Meal::firstOrCreate(['name' => 'pancho simple'], ['price' => 1500]);
    }
}
