<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * El orden respeta las claves foráneas: primero catálogos y usuarios,
     * y al final las tablas que relacionan a los anteriores.
     */
    public function run(): void
    {
        $this->call(RolSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(FoodTruckSeeder::class);
        $this->call(PDVSeeder::class);
        $this->call(FilterSeeder::class);
        $this->call(MealSeeder::class);
        $this->call(IngredientSeeder::class);
        $this->call(RecipeSeeder::class);
        $this->call(PDISeeder::class);
        $this->call(CentralStockSeeder::class);
        $this->call(FoodTruckStockSeeder::class);
        $this->call(OrderSeeder::class);
        $this->call(FoodTruckAssignmentSeeder::class);
        $this->call(OrderDetailSeeder::class);
        $this->call(PdiDetailSeeder::class);
        $this->call(PDVAssignmentSeeder::class);
    }
}
