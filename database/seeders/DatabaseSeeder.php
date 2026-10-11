<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolSeeder::class);
        $this->call(FoodTruckSeeder::class);
        $this->call(PDVSeeder::class);
        $this->call(MealSeeder::class);
        $this->call(IngredientSeeder::class);
        $this->call(PDISeeder::class);
        $this->call(CentralStockSeeder::class);

        User::firstOrCreate(['email' => 'alejo@gmail.com'], [
            'name' => 'alejo',
            'last_name' => 'acosta',
            'dni' => '43.702.748',
            'phone' => null,
            'password' => '1234',
            'role_id' => 1,
        ]);

        User::firstOrCreate(['email' => 'carlos@gmail.com'], [
            'name' => 'carlos',
            'last_name' => 'mendez',
            'dni' => '43.528.476',
            'phone' => null,
            'password' => '5678',
            'role_id' => 1,
        ]);

        User::firstOrCreate(['email' => 'ezequiel@gmail.com'], [
            'name' => 'ezequiel',
            'last_name' => 'vallejos',
            'dni' => '46.832.888',
            'phone' => null,
            'password' => '91011',
            'role_id' => 1,
        ]);

        User::firstOrCreate(['email' => 'facu@gmail.com'], [
            'name' => 'facundo',
            'last_name' => 'billalba',
            'dni' => '47.533.592',
            'phone' => '3764-198151',
            'password' => '12131',
            'role_id' => 1,
        ]);

        User::firstOrCreate(['email' => 'villalba@gmail.com'], [
            'name' => 'carlos',
            'last_name' => 'villalba',
            'dni' => '11.111.111',
            'phone' => null,
            'password' => '12131',
            'role_id' => 4,
        ]);

        User::firstOrCreate(['email' => 'elpepe@gmail.com'], [
            'name' => 'pepe',
            'last_name' => null,
            'dni' => '22.222.222',
            'phone' => null,
            'password' => '12131',
            'role_id' => 2,
        ]);

        User::firstOrCreate(['email' => 'empleado@gmail.com'], [
            'name' => 'juan',
            'last_name' => 'perez',
            'dni' => '33.333.333',
            'phone' => null,
            'password' => '1234',
            'role_id' => 3,
        ]);

        $this->call(OrderSeeder::class);
        $this->call(FoodTruckAssignmentSeeder::class);
        $this->call(OrderDetailSeeder::class);
        $this->call(PdiDetailSeeder::class);
        $this->call(PDVAssignmentSeeder::class);
        $this->call(RecipeSeeder::class);
        $this->call(FoodTruckStockSeeder::class);
    }
}
