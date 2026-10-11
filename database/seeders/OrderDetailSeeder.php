<?php

namespace Database\Seeders;

use App\Models\OrderDetail;
use Illuminate\Database\Seeder;

class OrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderDetail::firstOrCreate(['order_id' => 1, 'meal_id' => 1], ['quantity' => 2]);
        OrderDetail::firstOrCreate(['order_id' => 1, 'meal_id' => 2], ['quantity' => 1]);
    }
}
