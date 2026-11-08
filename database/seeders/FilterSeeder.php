<?php

namespace Database\Seeders;

use App\Models\Filter;
use Illuminate\Database\Seeder;

class FilterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * El primer filtro debe seguir siendo el de nombre nulo (id 1) y el
     * segundo "panchos especiales" (id 2): hay pruebas que usan esa id.
     */
    public function run(): void
    {
        $filters = [
            null,
            'panchos especiales',
            'panchos clasicos',
            'panchos picantes',
            'panchos con queso',
            'panchos con panceta',
            'panchos veggie',
            'combos',
            'bebidas',
            'promociones',
        ];

        foreach ($filters as $filter) {
            Filter::firstOrCreate(['name' => $filter]);
        }
    }
}
