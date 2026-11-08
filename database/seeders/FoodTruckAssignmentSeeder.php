<?php

namespace Database\Seeders;

use App\Models\FoodTruck;
use App\Models\FoodTruckAssignment;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Database\Seeder;

class FoodTruckAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleIds = Rol::whereIn('role', ['empleado', 'encargado_deposito'])->pluck('id');
        $users = User::whereIn('role_id', $roleIds)->orderBy('id')->get();
        $foodTrucks = FoodTruck::orderBy('id')->get();

        if ($users->isEmpty()) {
            return;
        }

        foreach ($foodTrucks as $index => $foodTruck) {
            FoodTruckAssignment::firstOrCreate([
                'food_truck_id' => $foodTruck->id,
                'user_id' => $users[$index % $users->count()]->id,
            ]);
        }
    }
}
