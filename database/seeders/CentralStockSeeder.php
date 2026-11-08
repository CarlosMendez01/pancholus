<?php

namespace Database\Seeders;

use App\Models\CentralStock;
use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class CentralStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Ingredient::orderBy('id')->get() as $index => $ingredient) {
            CentralStock::updateOrCreate(
                ['product_id' => $ingredient->id],
                ['quantity' => ($index + 1) * 25],
            );
        }
    }
}
