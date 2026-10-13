<?php

namespace Database\Seeders;

use App\Models\Recipe;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Recipe::firstOrCreate(['meal_id' => 1, 'ingredient_id' => 1], ['required_quantity' => 1]);
    }
}
