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
        $meals = [
            // --- ESPECIALES ---
            [
                'name' => 'superpancho',
                'price' => 2500,
                'details' => 'Salchicha gigante parrillera, doble capa de queso cheddar fundido y lluvia de crocante panceta ahumada.',
                'type' => 'especial'
            ],
            [
                'name' => 'pancho tejano',
                'price' => 2800,
                'details' => 'Salchicha ahumada, salsa barbacoa, cebolla crispy, panceta y queso provoleta rallado.',
                'type' => 'especial'
            ],
            [
                'name' => 'pancho picante',
                'price' => 2600,
                'details' => 'Salchicha con especias, queso pepper jack, jalapeños en rodajas y salsa alioli de ajo asado.',
                'type' => 'especial'
            ],
            [
                'name' => 'pancho cuatro quesos',
                'price' => 2700,
                'details' => 'Salchicha gigante con cubierta de mozzarella, cheddar, roquefort y parmesano gratinado.',
                'type' => 'especial'
            ],

            // --- NORMALES ---
            [
                'name' => 'pancho simple',
                'price' => 1500,
                'details' => 'Salchicha tradicional en pan artesanal suave, acompañado de papas pay.',
                'type' => 'normal'
            ],
            [
                'name' => 'pancho con queso',
                'price' => 1700,
                'details' => 'Salchicha clásica bañada en queso fundido y mostaza suave.',
                'type' => 'normal'
            ],
            [
                'name' => 'pancho completo',
                'price' => 1900,
                'details' => 'Salchicha tradicional con lluvia de papas pay, aderezo de la casa y salsa criolla.',
                'type' => 'normal'
            ],
            [
                'name' => 'pancho clasico con panceta',
                'price' => 2000,
                'details' => 'Salchicha tradicional envuelta en panceta dorada y ketchup de la casa.',
                'type' => 'normal'
            ],
        ];

        foreach ($meals as $meal) {
            Meal::create($meal);
        }
    }
}