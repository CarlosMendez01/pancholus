<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Meal;

class MealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Meal::create([
            'name' => 'superpancho',
            'price' => 2500
        ]);

        Meal::create([
            'name' => 'pancho simple',
            'price' => 1500
        ]);
    }
}
