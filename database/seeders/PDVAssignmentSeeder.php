<?php

namespace Database\Seeders;

use App\Models\PDV_assignment;
use Illuminate\Database\Seeder;

class PDVAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PDV_assignment::firstOrCreate(['PDV_id' => 1, 'food_truck_id' => 1]);
    }
}
