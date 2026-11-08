<?php

namespace Database\Seeders;

use App\Models\Meal;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Database\Seeder;

class OrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::orderBy('id')->get();
        $meals = Meal::orderBy('id')->get();

        if ($meals->isEmpty()) {
            return;
        }

        foreach ($orders as $orderIndex => $order) {
            for ($detail = 0; $detail < 2; $detail++) {
                $meal = $meals[($orderIndex + $detail) % $meals->count()];

                OrderDetail::firstOrCreate(
                    [
                        'order_id' => $order->id,
                        'meal_id' => $meal->id,
                    ],
                    ['quantity' => (($orderIndex + $detail) % 4) + 1],
                );
            }
        }
    }
}
