<?php

namespace Database\Seeders;

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
        $user = User::firstOrCreate(
            ['email' => 'villalba@gmail.com'],
            [
                'name' => 'carlos',
                'last_name' => 'villalba',
                'dni' => '11.111.111',
                'phone' => null,
                'password' => '12131',
                'role_id' => 4,
            ]
        );

        Order::firstOrCreate(['user_id' => $user->id, 'status' => Order::STATUS_PENDIENTE]);
    }
}
