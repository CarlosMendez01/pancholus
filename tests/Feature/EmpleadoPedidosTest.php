<?php

use App\Models\FoodTruck;
use App\Models\FoodTruckAssignment;
use App\Models\Order;
use App\Models\User;
use Database\Seeders\RolSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolSeeder::class);
});

/*
|------------------------------------------------------------------------------
| Empleado - Gestión de pedidos
|------------------------------------------------------------------------------
| El empleado solo opera sobre los pedidos de sus foodtrucks asignados.
| Cuenta con filtros por estado, auto-refresh y reglas de transición.
*/

function empleadoConFoodTruck(array $estados = []): array
{
    $empleado = User::factory()->create(['role_id' => 3]);
    $foodTruck = FoodTruck::factory()->create();

    FoodTruckAssignment::create([
        'food_truck_id' => $foodTruck->id,
        'user_id' => $empleado->id,
    ]);

    $pedidos = [];

    foreach ($estados as $estado) {
        $pedidos[$estado] = Order::factory()->create([
            'foodtruck_id' => $foodTruck->id,
            'status' => $estado,
        ]);
    }

    return [$empleado, $foodTruck, $pedidos];
}

test('el inicio del empleado cuenta solo los pedidos de su foodtruck', function () {
    [$empleado, $foodTruck] = empleadoConFoodTruck();

    $otroFoodTruck = FoodTruck::factory()->create();

    Order::factory()->count(2)->create([
        'foodtruck_id' => $foodTruck->id,
        'status' => Order::STATUS_PENDIENTE,
    ]);
    Order::factory()->create([
        'foodtruck_id' => $foodTruck->id,
        'status' => Order::STATUS_REALIZADO,
    ]);
    Order::factory()->create([
        'foodtruck_id' => $foodTruck->id,
        'status' => Order::STATUS_CANCELADO,
    ]);
    Order::factory()->create([
        'foodtruck_id' => $otroFoodTruck->id,
        'status' => Order::STATUS_PENDIENTE,
    ]);

    $this->actingAs($empleado)
        ->get(route('empleado.inicio'))
        ->assertOk()
        ->assertViewHas('pendingCount', 2)
        ->assertViewHas('realizedCount', 1)
        ->assertViewHas('cancelledCount', 1)
        ->assertSee($empleado->name);
});

test('el listado de pedidos filtra por estado', function () {
    [$empleado, , $pedidos] = empleadoConFoodTruck([
        Order::STATUS_PENDIENTE,
        Order::STATUS_REALIZADO,
        Order::STATUS_CANCELADO,
    ]);

    $response = $this->actingAs($empleado)
        ->get(route('empleado.pedidos.index', ['estado' => Order::STATUS_REALIZADO]))
        ->assertOk();

    $ids = $response->viewData('orders')->pluck('id')->all();

    expect($response->viewData('status'))->toBe(Order::STATUS_REALIZADO)
        ->and($ids)->toContain($pedidos[Order::STATUS_REALIZADO]->id)
        ->not->toContain($pedidos[Order::STATUS_PENDIENTE]->id)
        ->not->toContain($pedidos[Order::STATUS_CANCELADO]->id);
});

test('un estado de filtro inválido cae a pendiente', function () {
    [$empleado, , $pedidos] = empleadoConFoodTruck([
        Order::STATUS_PENDIENTE,
        Order::STATUS_REALIZADO,
    ]);

    $response = $this->actingAs($empleado)
        ->get(route('empleado.pedidos.index', ['estado' => 'inexistente']))
        ->assertOk();

    expect($response->viewData('status'))->toBe(Order::STATUS_PENDIENTE)
        ->and($response->viewData('orders')->pluck('id')->all())
        ->toBe([$pedidos[Order::STATUS_PENDIENTE]->id]);
});

test('el auto-refresh devuelve contadores, firma y el HTML filtrado', function () {
    [$empleado, , $pedidos] = empleadoConFoodTruck([
        Order::STATUS_PENDIENTE,
        Order::STATUS_REALIZADO,
    ]);

    $response = $this->actingAs($empleado)
        ->getJson(route('empleado.pedidos.actualizar', ['estado' => Order::STATUS_PENDIENTE]))
        ->assertOk()
        ->assertJsonStructure(['signature', 'counts', 'html']);

    $data = $response->json();

    expect($data['counts'][Order::STATUS_PENDIENTE])->toBe(1)
        ->and($data['counts'][Order::STATUS_REALIZADO])->toBe(1)
        ->and($data['html'])->toContain("#{$pedidos[Order::STATUS_PENDIENTE]->id}")
        ->and($data['html'])->not->toContain("#{$pedidos[Order::STATUS_REALIZADO]->id}");
});

test('un empleado no puede modificar pedidos de otro foodtruck', function () {
    [$empleado] = empleadoConFoodTruck();

    $otroFoodTruck = FoodTruck::factory()->create();
    $ajeno = Order::factory()->create([
        'foodtruck_id' => $otroFoodTruck->id,
        'status' => Order::STATUS_PENDIENTE,
    ]);

    $this->actingAs($empleado)
        ->post(route('empleado.pedidos.realizar', $ajeno), [
            'status' => Order::STATUS_REALIZADO,
        ])
        ->assertForbidden();

    expect($ajeno->fresh()->status)->toBe(Order::STATUS_PENDIENTE);
});

test('solo se pueden modificar pedidos pendientes', function () {
    [$empleado, , $pedidos] = empleadoConFoodTruck([Order::STATUS_REALIZADO]);

    $this->actingAs($empleado)
        ->from(route('empleado.pedidos.index'))
        ->post(route('empleado.pedidos.cancelar', $pedidos[Order::STATUS_REALIZADO]), [
            'status' => Order::STATUS_CANCELADO,
        ])
        ->assertRedirect(route('empleado.pedidos.index'))
        ->assertSessionHas('error');

    expect($pedidos[Order::STATUS_REALIZADO]->fresh()->status)->toBe(Order::STATUS_REALIZADO);
});

test('el cambio de estado valida el estado permitido', function () {
    [$empleado, , $pedidos] = empleadoConFoodTruck([Order::STATUS_PENDIENTE]);

    $this->actingAs($empleado)
        ->post(route('empleado.pedidos.realizar', $pedidos[Order::STATUS_PENDIENTE]), [
            'status' => 'volando',
        ])
        ->assertSessionHasErrors('status');

    expect($pedidos[Order::STATUS_PENDIENTE]->fresh()->status)->toBe(Order::STATUS_PENDIENTE);
});

test('solo se pueden eliminar pedidos finalizados', function () {
    [$empleado, , $pedidos] = empleadoConFoodTruck([Order::STATUS_PENDIENTE]);

    $this->actingAs($empleado)
        ->from(route('empleado.pedidos.index'))
        ->delete(route('empleado.pedidos.destroy', $pedidos[Order::STATUS_PENDIENTE]))
        ->assertRedirect(route('empleado.pedidos.index'))
        ->assertSessionHas('error');

    $this->assertNotSoftDeleted('orders', ['id' => $pedidos[Order::STATUS_PENDIENTE]->id]);
});

test('un empleado no puede eliminar pedidos de otro foodtruck', function () {
    [$empleado] = empleadoConFoodTruck();

    $ajeno = Order::factory()->create([
        'foodtruck_id' => FoodTruck::factory()->create()->id,
        'status' => Order::STATUS_REALIZADO,
    ]);

    $this->actingAs($empleado)
        ->delete(route('empleado.pedidos.destroy', $ajeno))
        ->assertForbidden();

    $this->assertNotSoftDeleted('orders', ['id' => $ajeno->id]);
});

test('un cliente no puede acceder al área de empleados', function () {
    $cliente = User::factory()->create(['role_id' => 1]);

    $this->actingAs($cliente)
        ->get(route('empleado.inicio'))
        ->assertForbidden();

    $this->actingAs($cliente)
        ->getJson(route('empleado.pedidos.actualizar'))
        ->assertForbidden();
});
