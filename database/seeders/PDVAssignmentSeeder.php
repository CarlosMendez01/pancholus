<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PDV_assignment;

class PDVAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PDV_assignment::create([
            'PDV_id' => 1,
            'food_truck_id' => 1
        ]);
    }
}
