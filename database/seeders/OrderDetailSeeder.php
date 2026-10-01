<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrderDetail;

class OrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderDetail::create([
            'order_id' => 1,
            'meal_id' => 1,
            'quantity' => 2
        ]);

        OrderDetail::create([
            'order_id' => 1,
            'meal_id' => 2,
            'quantity' => 1
        ]);
    }
}
