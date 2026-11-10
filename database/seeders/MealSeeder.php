<?php

namespace Database\Seeders;

use App\Models\Filter;
use App\Models\Meal;
use Illuminate\Database\Seeder;

class MealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultFilterId = Filter::whereNull('name')->value('id');
        $filters = Filter::whereNotNull('name')->pluck('id', 'name');

        $meals = [
            // --- ESPECIALES ---
            [
                'name' => 'superpancho',
                'price' => 2500,
                'details' => 'Salchicha gigante parrillera, doble capa de queso cheddar fundido y lluvia de crocante panceta ahumada.',
                'filter' => 'panchos especiales',
            ],
            [
                'name' => 'pancho tejano',
                'price' => 2800,
                'details' => 'Salchicha ahumada, salsa barbacoa, cebolla crispy, panceta y queso provoleta rallado.',
                'filter' => 'panchos especiales',
            ],
            [
                'name' => 'pancho picante',
                'price' => 2600,
                'details' => 'Salchicha con especias, queso pepper jack, jalapeños en rodajas y salsa alioli de ajo asado.',
                'filter' => 'panchos picantes',
            ],
            [
                'name' => 'pancho cuatro quesos',
                'price' => 2700,
                'details' => 'Salchicha gigante con cubierta de mozzarella, cheddar, roquefort y parmesano gratinado.',
                'filter' => 'panchos con queso',
            ],
            [
                'name' => 'pancho mexicano',
                'price' => 2900,
                'details' => 'Salchicha con especias, guacamole, jalapeños, cebolla morada y salsa picante.',
                'filter' => 'panchos picantes',
            ],
            [
                'name' => 'pancho doble cheddar',
                'price' => 3000,
                'details' => 'Salchicha gigante con doble porción de cheddar fundido, panceta y cebolla crispy.',
                'filter' => 'panchos con queso',
            ],

            // --- NORMALES ---
            [
                'name' => 'pancho simple',
                'price' => 1500,
                'details' => 'Salchicha tradicional en pan artesanal suave, acompañado de papas pay.',
                'filter' => 'panchos clasicos',
            ],
            [
                'name' => 'pancho con queso',
                'price' => 1700,
                'details' => 'Salchicha clásica bañada en queso fundido y mostaza suave.',
                'filter' => 'panchos con queso',
            ],
            [
                'name' => 'pancho completo',
                'price' => 1900,
                'details' => 'Salchicha tradicional con lluvia de papas pay, aderezo de la casa y salsa criolla.',
                'filter' => 'panchos clasicos',
            ],
            [
                'name' => 'pancho clasico con panceta',
                'price' => 2000,
                'details' => 'Salchicha tradicional envuelta en panceta dorada y ketchup de la casa.',
                'filter' => 'panchos con panceta',
            ],

            // --- VEGGIE ---
            [
                'name' => 'pancho veggie',
                'price' => 2300,
                'details' => 'Salchicha veggie en pan integral con queso vegano, lechuga y tomate fresco.',
                'filter' => 'panchos veggie',
            ],
            [
                'name' => 'pancho mediterraneo',
                'price' => 2400,
                'details' => 'Salchicha tradicional con mozzarella, tomate, lechuga, aceitunas y orégano.',
                'filter' => 'panchos veggie',
            ],
        ];

        foreach ($meals as $meal) {
            $filterId = $meal['filter'] === null
                ? $defaultFilterId
                : $filters[$meal['filter']];

            // Ruta relativa dentro de storage/app/public (servida vía /storage).
            // 'superpancho' tiene foto propia; el resto usa la genérica.
            $path = $meal['name'] === 'superpancho' ? 'superpancho.png' : 'pancho.png';

            Meal::updateOrCreate(
                ['name' => $meal['name']],
                [
                    'price' => $meal['price'],
                    'details' => $meal['details'],
                    'path' => $path,
                    'filter_id' => $filterId,
                ],
            );
        }
    }
}
