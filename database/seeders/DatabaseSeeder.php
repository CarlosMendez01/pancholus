<?php

namespace Database\Seeders;

use App\Models\FoodTruckAssignment;
use App\Models\FoodTruckStock;
use App\Models\OrderDetail;
use App\Models\PdiDetail;
use App\Models\PDV_assignment;
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

         User::factory()->create([
             'name' => 'alejo',
             'last_name' => 'acosta',
             'dni' => '43.702.748',
             'phone' => null,
             'email' => 'alejo@gmail.com',
             'password' => '1234',
             'role_id' => 1,
         ]);

         User::factory()->create([
             'name' => 'carlos',
             'last_name' => 'mendez',
             'dni' => '43.528.476',
             'phone' => null,
             'email' => 'carlos@gmail.com',
             'password' => '5678',
             'role_id' => 1,
         ]);

         User::factory()->create([
             'name' => 'ezequiel',
             'last_name' => 'vallejos',
             'dni' => '46.832.888',
             'phone' => null,
             'email' => 'ezequiel@gmail.com',
             'password' => '91011',
             'role_id' => 1,
         ]);

         User::factory()->create([
             'name' => 'facundo',
             'last_name' => 'billalba',
             'dni' => '47.533.592',
             'phone' => '3764-198151',
             'email' => 'facu@gmail.com',
             'password' => '12131',
             'role_id' => 1,
         ]);

         User::factory()->create([
             'name' => 'carlos',
             'last_name' => 'villalba',
             'dni' => '11.111.111',
             'phone' => null,
             'email' => 'villalba@gmail.com',
             'password' => '12131',
             'role_id' => 4,
         ]);

         User::factory()->create([
             'name' => 'pepe',
             'last_name' => null,
             'dni' => '22.222.222',
             'phone' => null,
             'email' => 'elpepe@gmail.com',
             'password' => '12131',
             'role_id' => 2,
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
