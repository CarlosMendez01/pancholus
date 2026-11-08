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
        // El orden importa: `salchicha` debe quedar con id 1 porque las
        // semillas de stock la referencian históricamente con ese id.
        $ingredients = [
            // Salchichas
            ['name' => 'salchicha', 'price' => 500, 'expiration_date' => '2056-09-15'],
            ['name' => 'salchicha gigante', 'price' => 700, 'expiration_date' => '2056-09-15'],
            ['name' => 'salchicha ahumada', 'price' => 650, 'expiration_date' => '2056-09-15'],
            ['name' => 'salchicha con especias', 'price' => 550, 'expiration_date' => '2056-09-15'],
            ['name' => 'salchicha veggie', 'price' => 600, 'expiration_date' => '2056-09-15'],

            // Panes
            ['name' => 'pan artesanal', 'price' => 300, 'expiration_date' => '2026-12-31'],
            ['name' => 'pan de pancho', 'price' => 250, 'expiration_date' => '2026-12-31'],
            ['name' => 'pan integral', 'price' => 350, 'expiration_date' => '2026-12-31'],

            // Quesos
            ['name' => 'queso cheddar', 'price' => 400, 'expiration_date' => '2026-11-30'],
            ['name' => 'queso mozzarella', 'price' => 450, 'expiration_date' => '2026-11-30'],
            ['name' => 'queso roquefort', 'price' => 600, 'expiration_date' => '2026-11-30'],
            ['name' => 'queso parmesano', 'price' => 700, 'expiration_date' => '2026-11-30'],
            ['name' => 'queso provoleta', 'price' => 550, 'expiration_date' => '2026-11-30'],
            ['name' => 'queso pepper jack', 'price' => 650, 'expiration_date' => '2026-11-30'],
            ['name' => 'queso vegano', 'price' => 500, 'expiration_date' => '2026-11-30'],

            // Aderezos y salsas
            ['name' => 'mayonesa', 'price' => 600, 'expiration_date' => '2096-10-01'],
            ['name' => 'mostaza', 'price' => 150, 'expiration_date' => '2026-12-31'],
            ['name' => 'ketchup', 'price' => 150, 'expiration_date' => '2026-12-31'],
            ['name' => 'salsa barbacoa', 'price' => 250, 'expiration_date' => '2026-12-31'],
            ['name' => 'salsa alioli de ajo asado', 'price' => 300, 'expiration_date' => '2026-12-31'],
            ['name' => 'salsa criolla', 'price' => 250, 'expiration_date' => '2026-12-31'],
            ['name' => 'aderezo de la casa', 'price' => 200, 'expiration_date' => '2026-12-31'],
            ['name' => 'salsa picante', 'price' => 200, 'expiration_date' => '2026-12-31'],
            ['name' => 'chimichurri', 'price' => 200, 'expiration_date' => '2026-12-31'],
            ['name' => 'guacamole', 'price' => 350, 'expiration_date' => '2026-11-30'],

            // Extras
            ['name' => 'panceta ahumada', 'price' => 800, 'expiration_date' => '2026-11-30'],
            ['name' => 'cebolla crispy', 'price' => 350, 'expiration_date' => '2026-12-31'],
            ['name' => 'cebolla morada', 'price' => 180, 'expiration_date' => '2026-12-31'],
            ['name' => 'jalapeños', 'price' => 300, 'expiration_date' => '2026-12-31'],
            ['name' => 'papas pay', 'price' => 200, 'expiration_date' => '2026-12-31'],
            ['name' => 'lechuga', 'price' => 150, 'expiration_date' => '2026-11-30'],
            ['name' => 'tomate', 'price' => 180, 'expiration_date' => '2026-11-30'],
            ['name' => 'palta', 'price' => 450, 'expiration_date' => '2026-11-30'],
            ['name' => 'morrón', 'price' => 220, 'expiration_date' => '2026-11-30'],
            ['name' => 'aceitunas', 'price' => 300, 'expiration_date' => '2027-01-31'],
            ['name' => 'orégano', 'price' => 100, 'expiration_date' => '2027-06-30'],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::updateOrCreate(['name' => $ingredient['name']], $ingredient);
        }
    }
}
