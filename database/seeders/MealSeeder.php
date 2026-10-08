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
            'price' => 2500,
            'details' => 'Salchicha gigante parrillera, doble capa de queso cheddar fundido y lluvia de crocante panceta ahumada.',
            'type' => 'especial'
        ]);

        Meal::create([
            'name' => 'pancho simple',
            'price' => 1500,
            'details' => 'Salchicha gigante parrillera, doble capa de queso cheddar fundido y lluvia de crocante panceta ahumada.',
            'type' => 'normal'
        ]);
    }
}
