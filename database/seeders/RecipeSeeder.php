<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Recipe;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Recipe::create([
            'meal_id' => 1,
            'ingredient_id' => 1,
            'required_quantity' => 1
        ]);
    }
}
