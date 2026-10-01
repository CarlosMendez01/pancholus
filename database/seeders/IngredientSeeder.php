<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ingredient::create([
            'name' => 'salchicha',
            'price' => 500,
            'expiration_date' => '2056-09-15',
        ]);

        Ingredient::create([
            'name' => 'mayonesa',
            'price' => 600,
            'expiration_date' => '2096-10-01',
        ]);
    }
}
