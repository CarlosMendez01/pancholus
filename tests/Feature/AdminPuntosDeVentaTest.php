<?php

use App\Models\FoodTruck;
use App\Models\PDV;
use App\Models\PDV_assignment;
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
| Administración - Puntos de venta
|------------------------------------------------------------------------------
| Cada punto de venta tiene coordenadas y puede (o no) estar asignado a una
| foodtruck. Al eliminar una asignación se conserva el punto de venta.
*/

test('un administrador ve el listado de puntos de venta', function () {
    $foodTruck = FoodTruck::factory()->create(['license_plate' => 'PDVTRUCK']);
    $pdv = PDV::create(['latitude' => -27.37, 'longitude' => -55.90]);
    PDV_assignment::create([
        'PDV_id' => $pdv->id,
        'food_truck_id' => $foodTruck->id,
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.pdvs.index'))
        ->assertOk()
        ->assertSee('PDVTRUCK')
        ->assertViewHas('pdvAssignments', fn ($asignaciones) => $asignaciones->total() === 1);
});

test('un administrador ve el formulario para crear un punto de venta', function () {
    FoodTruck::factory()->create(['license_plate' => 'DISPONIBLE']);

    $this->actingAs($this->admin)
        ->get(route('admin.pdvs.create'))
        ->assertOk()
        ->assertSee('DISPONIBLE')
        ->assertViewHas('foodTrucks', fn ($foodTrucks) => $foodTrucks->isNotEmpty());
});

test('un administrador crea un punto de venta con foodtruck asignada', function () {
    $foodTruck = FoodTruck::factory()->create();

    $this->actingAs($this->admin)
        ->post(route('admin.pdvs.store'), [
            'latitude' => -27.367,
            'longitude' => -55.896,
            'food_truck_id' => $foodTruck->id,
        ])
        ->assertRedirect(route('admin.pdvs.index'))
        ->assertSessionHas('success');

    $pdv = PDV::firstWhere('latitude', -27.367);

    expect($pdv)->not->toBeNull()
        ->and((float) $pdv->longitude)->toBe(-55.896);

    $this->assertDatabaseHas('p_d_v_assignments', [
        'PDV_id' => $pdv->id,
        'food_truck_id' => $foodTruck->id,
    ]);
});

test('un punto de venta puede crearse sin foodtruck asignada', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.pdvs.store'), [
            'latitude' => -27.30,
            'longitude' => -55.80,
        ])
        ->assertRedirect(route('admin.pdvs.index'));

    $pdv = PDV::firstWhere('latitude', -27.30);

    $this->assertDatabaseHas('p_d_v_assignments', [
        'PDV_id' => $pdv->id,
        'food_truck_id' => null,
    ]);
});

test('el alta de punto de venta valida coordenadas y foodtruck existente', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.pdvs.store'), [
            'latitude' => 91,
            'longitude' => 181,
            'food_truck_id' => 999,
        ])
        ->assertSessionHasErrors(['latitude', 'longitude', 'food_truck_id']);

    $this->assertDatabaseCount('p_d_v_s', 0);
});

test('un administrador ve el formulario de edición de un punto de venta', function () {
    $pdv = PDV::create(['latitude' => -27.37, 'longitude' => -55.90]);
    $foodTruck = FoodTruck::factory()->create(['license_plate' => 'EDITABLE']);
    $asignacion = PDV_assignment::create([
        'PDV_id' => $pdv->id,
        'food_truck_id' => $foodTruck->id,
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.pdvs.edit', $asignacion))
        ->assertOk()
        ->assertSee('EDITABLE')
        ->assertViewHas('assignment', fn ($vista) => $vista->id === $asignacion->id);
});

test('un administrador actualiza coordenadas y foodtruck de un punto de venta', function () {
    $pdv = PDV::create(['latitude' => -27.37, 'longitude' => -55.90]);
    $asignacion = PDV_assignment::create([
        'PDV_id' => $pdv->id,
        'food_truck_id' => FoodTruck::factory()->create()->id,
    ]);
    $nuevaFoodTruck = FoodTruck::factory()->create();

    $this->actingAs($this->admin)
        ->post(route('admin.pdvs.update', $asignacion), [
            'latitude' => -30.50,
            'longitude' => -60.25,
            'food_truck_id' => $nuevaFoodTruck->id,
        ])
        ->assertRedirect(route('admin.pdvs.index'))
        ->assertSessionHas('success');

    expect((float) $pdv->fresh()->latitude)->toBe(-30.50)
        ->and((float) $pdv->fresh()->longitude)->toBe(-60.25)
        ->and($asignacion->fresh()->food_truck_id)->toBe($nuevaFoodTruck->id);
});

test('la edición de punto de venta exige una foodtruck existente', function () {
    $pdv = PDV::create(['latitude' => -27.37, 'longitude' => -55.90]);
    $asignacion = PDV_assignment::create([
        'PDV_id' => $pdv->id,
        'food_truck_id' => FoodTruck::factory()->create()->id,
    ]);

    $this->actingAs($this->admin)
        ->post(route('admin.pdvs.update', $asignacion), [
            'latitude' => -30.50,
            'longitude' => -60.25,
            'food_truck_id' => 999,
        ])
        ->assertSessionHasErrors('food_truck_id');
});

test('eliminar un punto de venta borra la asignación pero conserva sus coordenadas', function () {
    $pdv = PDV::create(['latitude' => -27.37, 'longitude' => -55.90]);
    $asignacion = PDV_assignment::create([
        'PDV_id' => $pdv->id,
        'food_truck_id' => FoodTruck::factory()->create()->id,
    ]);

    $this->actingAs($this->admin)
        ->from(route('admin.pdvs.index'))
        ->delete(route('admin.pdvs.destroy', $asignacion))
        ->assertRedirect(route('admin.pdvs.index'));

    $this->assertSoftDeleted('p_d_v_assignments', ['id' => $asignacion->id]);
    $this->assertDatabaseHas('p_d_v_s', ['id' => $pdv->id]);
});

test('un empleado no puede gestionar puntos de venta', function () {
    $empleado = User::factory()->create(['role_id' => 3]);

    $this->actingAs($empleado)
        ->get(route('admin.pdvs.index'))
        ->assertForbidden();

    $this->actingAs($empleado)
        ->post(route('admin.pdvs.store'), [
            'latitude' => -27.37,
            'longitude' => -55.90,
        ])
        ->assertForbidden();
});
