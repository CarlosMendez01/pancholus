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
| Administración - Foodtrucks
|------------------------------------------------------------------------------
| CRUD de unidades con patente única y estado habilitado/inhabilitado.
| Al eliminar una unidad se eliminan también sus asignaciones.
*/

test('un administrador ve el listado de foodtrucks', function () {
    $foodTruck = FoodTruck::factory()->create(['license_plate' => 'ABC123']);

    $this->actingAs($this->admin)
        ->get(route('admin.foodtrucks.index'))
        ->assertOk()
        ->assertSee('ABC123')
        ->assertViewHas('foodTrucks', fn ($foodTrucks) => $foodTrucks->contains('id', $foodTruck->id));
});

test('un administrador ve el formulario para crear una foodtruck', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.foodtrucks.create'))
        ->assertOk()
        ->assertSee('Agregar foodtruck');
});

test('un administrador registra una foodtruck habilitada por defecto', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.foodtrucks.store'), [
            'license_plate' => 'NUEVA01',
        ])
        ->assertRedirect(route('admin.foodtrucks.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('food_trucks', [
        'license_plate' => 'NUEVA01',
        'status' => 'habilitado',
    ]);
});

test('el alta de foodtruck exige una patente y no admite repetidas', function () {
    FoodTruck::factory()->create(['license_plate' => 'REPETIDA']);

    $this->actingAs($this->admin)
        ->post(route('admin.foodtrucks.store'), ['license_plate' => ''])
        ->assertSessionHasErrors('license_plate');

    $this->actingAs($this->admin)
        ->post(route('admin.foodtrucks.store'), ['license_plate' => 'REPETIDA'])
        ->assertSessionHasErrors('license_plate');
});

test('un administrador ve el formulario de edición de una foodtruck', function () {
    $foodTruck = FoodTruck::factory()->create();

    $this->actingAs($this->admin)
        ->get(route('admin.foodtrucks.edit', $foodTruck))
        ->assertOk()
        ->assertViewHas('foodTruck', fn ($vista) => $vista->id === $foodTruck->id);
});

test('un administrador actualiza la patente y el estado de una foodtruck', function () {
    $foodTruck = FoodTruck::factory()->create([
        'license_plate' => 'VIEJA01',
        'status' => 'habilitado',
    ]);

    $this->actingAs($this->admin)
        ->post(route('admin.foodtrucks.update', $foodTruck), [
            'license_plate' => 'NUEVA02',
            'status' => 'inhabilitado',
        ])
        ->assertRedirect(route('admin.foodtrucks.index'))
        ->assertSessionHas('success');

    expect($foodTruck->fresh())
        ->license_plate->toBe('NUEVA02')
        ->status->toBe('inhabilitado');
});

test('mantener la propia patente no da error de duplicado', function () {
    $foodTruck = FoodTruck::factory()->create([
        'license_plate' => 'MISMA01',
        'status' => 'habilitado',
    ]);

    $this->actingAs($this->admin)
        ->post(route('admin.foodtrucks.update', $foodTruck), [
            'license_plate' => 'MISMA01',
            'status' => 'inhabilitado',
        ])
        ->assertSessionHasNoErrors();

    expect($foodTruck->fresh()->status)->toBe('inhabilitado');
});

test('la edición de foodtruck valida patente repetida y estado inválido', function () {
    FoodTruck::factory()->create(['license_plate' => 'OCUPADA']);
    $foodTruck = FoodTruck::factory()->create(['license_plate' => 'LIBRE001']);

    $this->actingAs($this->admin)
        ->post(route('admin.foodtrucks.update', $foodTruck), [
            'license_plate' => 'OCUPADA',
            'status' => 'volando',
        ])
        ->assertSessionHasErrors(['license_plate', 'status']);
});

test('al eliminar una foodtruck también se eliminan sus asignaciones', function () {
    $foodTruck = FoodTruck::factory()->create();
    $empleado = User::factory()->create(['role_id' => 3]);
    $asignacion = FoodTruckAssignment::create([
        'food_truck_id' => $foodTruck->id,
        'user_id' => $empleado->id,
    ]);

    $this->actingAs($this->admin)
        ->from(route('admin.foodtrucks.index'))
        ->delete(route('admin.foodtrucks.destroy', $foodTruck))
        ->assertRedirect(route('admin.foodtrucks.index'))
        ->assertSessionHas('success');

    $this->assertSoftDeleted('food_trucks', ['id' => $foodTruck->id]);
    $this->assertSoftDeleted('food_truck_assignments', ['id' => $asignacion->id]);
});

test('un empleado no puede gestionar foodtrucks', function () {
    $empleado = User::factory()->create(['role_id' => 3]);

    $this->actingAs($empleado)
        ->get(route('admin.foodtrucks.index'))
        ->assertForbidden();

    $this->actingAs($empleado)
        ->post(route('admin.foodtrucks.store'), ['license_plate' => 'NOAUTORIZADA'])
        ->assertForbidden();
});
