<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ingredient::firstOrCreate(['name' => 'salchicha'], [
            'price' => 500,
            'expiration_date' => '2056-09-15',
        ]);

        Ingredient::firstOrCreate(['name' => 'mayonesa'], [
            'price' => 600,
            'expiration_date' => '2096-10-01',
        ]);
    }
}
