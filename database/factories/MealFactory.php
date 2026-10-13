<?php

namespace Database\Factories;

use App\Models\Filter;
use App\Models\Meal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Meal>
 */
class MealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'price' => fake()->randomFloat(2, 500, 5000),
            'details' => fake()->sentence(),
            'filter_id' => Filter::factory(),
        ];
    }
}
