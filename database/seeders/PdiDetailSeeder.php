<?php

namespace Database\Seeders;

use App\Models\Meal;
use App\Models\PDI;
use App\Models\PdiDetail;
use Illuminate\Database\Seeder;

class PdiDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pdis = PDI::orderBy('id')->get();
        $meals = Meal::orderBy('id')->get();

        if ($meals->isEmpty()) {
            return;
        }

        foreach ($pdis as $pdiIndex => $pdi) {
            for ($detail = 0; $detail < 2; $detail++) {
                $meal = $meals[($pdiIndex + $detail) % $meals->count()];

                PdiDetail::firstOrCreate(
                    [
                        'PDI_id' => $pdi->id,
                        'meal_id' => $meal->id,
                    ],
                    ['quantity' => (($pdiIndex + $detail) % 10) + 1],
                );
            }
        }
    }
}
