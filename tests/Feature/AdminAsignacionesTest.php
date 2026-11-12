<?php

use App\Models\FoodTruck;
use App\Models\FoodTruckAssignment;
use App\Models\User;
use Database\Seeders\RolSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolSeeder::class);

    $this->admin = User::factory()->create(['role_id' => 2]);
});

/*
|------------------------------------------------------------------------------
| Administración - Asignaciones de empleados a foodtrucks
|------------------------------------------------------------------------------
| El administrador consulta las asignaciones, conoce el máximo de empleados
| por unidad y puede quitar una asignación.
*/

test('un administrador ve las asignaciones agrupadas por foodtruck', function () {
    $foodTruck = FoodTruck::factory()->create(['license_plate' => 'ASIG001']);
    $empleado = User::factory()->create([
        'role_id' => 3,
        'name' => 'Ana',
        'last_name' => 'Álvarez',
    ]);

    FoodTruckAssignment::create([
        'food_truck_id' => $foodTruck->id,
        'user_id' => $empleado->id,
    ]);

    $response = $this->actingAs($this->admin)
        ->get(route('admin.asignaciones.index'))
        ->assertOk()
        ->assertSee('ASIG001')
        ->assertSee('Ana')
        ->assertViewHas('maxEmpleados', FoodTruck::MAX_EMPLEADOS);

    $unidad = $response->viewData('foodTrucks')->firstWhere('id', $foodTruck->id);

    expect($unidad)->not->toBeNull()
        ->and($unidad->assignments)->toHaveCount(1)
        ->and($unidad->assignments->first()->user->id)->toBe($empleado->id);
});

test('la vista carga los datos de todos los empleados asignados', function () {
    $foodTruck = FoodTruck::factory()->create();

    $zurdo = User::factory()->create(['role_id' => 3, 'name' => 'Bruno', 'last_name' => 'Zurdo']);
    $alvarez = User::factory()->create(['role_id' => 3, 'name' => 'Ana', 'last_name' => 'Álvarez']);

    FoodTruckAssignment::create(['food_truck_id' => $foodTruck->id, 'user_id' => $zurdo->id]);
    FoodTruckAssignment::create(['food_truck_id' => $foodTruck->id, 'user_id' => $alvarez->id]);

    $response = $this->actingAs($this->admin)
        ->get(route('admin.asignaciones.index'))
        ->assertOk();

    $unidad = $response->viewData('foodTrucks')->firstWhere('id', $foodTruck->id);

    expect($unidad->assignments)->toHaveCount(2)
        ->and($unidad->assignments->pluck('user.id')->all())
        ->toContain($zurdo->id)
        ->toContain($alvarez->id);
});

test('un administrador quita una asignación sin eliminar al empleado ni la foodtruck', function () {
    $foodTruck = FoodTruck::factory()->create();
    $empleado = User::factory()->create(['role_id' => 3]);
    $asignacion = FoodTruckAssignment::create([
        'food_truck_id' => $foodTruck->id,
        'user_id' => $empleado->id,
    ]);

    $this->actingAs($this->admin)
        ->from(route('admin.asignaciones.index'))
        ->delete(route('admin.asignaciones.destroy', $asignacion))
        ->assertRedirect(route('admin.asignaciones.index'));

    $this->assertSoftDeleted('food_truck_assignments', ['id' => $asignacion->id]);
    $this->assertDatabaseHas('users', ['id' => $empleado->id]);
    $this->assertDatabaseHas('food_trucks', ['id' => $foodTruck->id]);
});

test('un empleado no puede ver ni quitar asignaciones', function () {
    $empleado = User::factory()->create(['role_id' => 3]);
    $foodTruck = FoodTruck::factory()->create();
    $asignacion = FoodTruckAssignment::create([
        'food_truck_id' => $foodTruck->id,
        'user_id' => $empleado->id,
    ]);

    $this->actingAs($empleado)
        ->get(route('admin.asignaciones.index'))
        ->assertForbidden();

    $this->actingAs($empleado)
        ->delete(route('admin.asignaciones.destroy', $asignacion))
        ->assertForbidden();
});
