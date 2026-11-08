<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Meal;
use App\Models\Recipe;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Cada receta vincula una comida con los ingredientes que la componen.
     * Se buscan por nombre (y no por id) para que las semillas funcionen
     * sin importar el orden en que se hayan creado las demás tablas.
     */
    public function run(): void
    {
        $recipes = [
            'superpancho' => [
                'salchicha gigante' => 1,
                'pan artesanal' => 1,
                'queso cheddar' => 2,
                'panceta ahumada' => 1,
            ],
            'pancho tejano' => [
                'salchicha ahumada' => 1,
                'pan artesanal' => 1,
                'salsa barbacoa' => 1,
                'cebolla crispy' => 1,
                'panceta ahumada' => 1,
                'queso provoleta' => 1,
            ],
            'pancho picante' => [
                'salchicha con especias' => 1,
                'pan artesanal' => 1,
                'queso pepper jack' => 1,
                'jalapeños' => 1,
                'salsa alioli de ajo asado' => 1,
            ],
            'pancho cuatro quesos' => [
                'salchicha gigante' => 1,
                'pan artesanal' => 1,
                'queso mozzarella' => 1,
                'queso cheddar' => 1,
                'queso roquefort' => 1,
                'queso parmesano' => 1,
            ],
            'pancho mexicano' => [
                'salchicha con especias' => 1,
                'pan artesanal' => 1,
                'guacamole' => 1,
                'jalapeños' => 1,
                'cebolla morada' => 1,
                'salsa picante' => 1,
            ],
            'pancho doble cheddar' => [
                'salchicha gigante' => 1,
                'pan artesanal' => 1,
                'queso cheddar' => 2,
                'panceta ahumada' => 1,
                'cebolla crispy' => 1,
            ],
            'pancho simple' => [
                'salchicha' => 1,
                'pan artesanal' => 1,
                'papas pay' => 1,
            ],
            'pancho con queso' => [
                'salchicha' => 1,
                'pan artesanal' => 1,
                'queso cheddar' => 1,
                'mostaza' => 1,
            ],
            'pancho completo' => [
                'salchicha' => 1,
                'pan artesanal' => 1,
                'papas pay' => 1,
                'aderezo de la casa' => 1,
                'salsa criolla' => 1,
            ],
            'pancho clasico con panceta' => [
                'salchicha' => 1,
                'pan artesanal' => 1,
                'panceta ahumada' => 1,
                'ketchup' => 1,
            ],
            'pancho veggie' => [
                'salchicha veggie' => 1,
                'pan integral' => 1,
                'queso vegano' => 1,
                'lechuga' => 1,
                'tomate' => 1,
                'palta' => 1,
            ],
            'pancho mediterraneo' => [
                'salchicha' => 1,
                'pan artesanal' => 1,
                'queso mozzarella' => 1,
                'tomate' => 1,
                'lechuga' => 1,
                'aceitunas' => 1,
                'orégano' => 1,
                'morrón' => 1,
                'chimichurri' => 1,
            ],
        ];

        foreach ($recipes as $mealName => $ingredients) {
            $meal = Meal::firstWhere('name', $mealName);
            $ingredientModels = Ingredient::whereIn('name', array_keys($ingredients))
                ->get()
                ->keyBy('name');

            if (! $meal) {
                continue;
            }

            foreach ($ingredients as $ingredientName => $requiredQuantity) {
                $ingredient = $ingredientModels->get($ingredientName);

                if (! $ingredient) {
                    continue;
                }

                Recipe::updateOrCreate(
                    [
                        'meal_id' => $meal->id,
                        'ingredient_id' => $ingredient->id,
                    ],
                    ['required_quantity' => $requiredQuantity],
                );
            }
        }
    }
}
