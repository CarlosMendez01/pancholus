<?php

namespace Database\Factories;

use App\Models\FoodTruck;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'foodtruck_id' => FoodTruck::factory(),
            'status' => Order::STATUS_PENDIENTE,
        ];
    }
}
