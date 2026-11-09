<?php

namespace Database\Seeders;

use App\Models\FoodTruck;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $foodTrucks = FoodTruck::orderBy('id')->pluck('id');

        if ($foodTrucks->isEmpty()) {
            return;
        }

        $orders = [
            ['email' => 'alejo@gmail.com', 'status' => Order::STATUS_PENDIENTE],
            ['email' => 'carlos@gmail.com', 'status' => Order::STATUS_PENDIENTE],
            ['email' => 'ezequiel@gmail.com', 'status' => Order::STATUS_REALIZADO],
            ['email' => 'facu@gmail.com', 'status' => Order::STATUS_REALIZADO],
            ['email' => 'maria.gomez@gmail.com', 'status' => Order::STATUS_PENDIENTE],
            ['email' => 'lucia.fernandez@gmail.com', 'status' => Order::STATUS_CANCELADO],
            ['email' => 'sofia.rojas@gmail.com', 'status' => Order::STATUS_PENDIENTE],
            ['email' => 'pedro.ramirez@gmail.com', 'status' => Order::STATUS_REALIZADO],
            ['email' => 'ana.torres@gmail.com', 'status' => Order::STATUS_PENDIENTE],
            ['email' => 'jorge.silva@gmail.com', 'status' => Order::STATUS_CANCELADO],
            ['email' => 'alejo@gmail.com', 'status' => Order::STATUS_REALIZADO],
            ['email' => 'carlos@gmail.com', 'status' => Order::STATUS_CANCELADO],
        ];

        foreach ($orders as $index => $order) {
            $user = User::firstWhere('email', $order['email']);

            if (! $user) {
                continue;
            }

            Order::firstOrCreate([
                'user_id' => $user->id,
                'foodtruck_id' => $foodTrucks[$index % $foodTrucks->count()],
                'status' => $order['status'],
            ]);
        }
    }
}
