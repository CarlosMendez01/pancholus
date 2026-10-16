<?php

use App\Models\Meal;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Database\Seeders\RolSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolSeeder::class);
});

/*
|------------------------------------------------------------------------------
| CRUD de pedidos
|------------------------------------------------------------------------------
| Crear: el cliente confirma un pedido y queda pendiente con sus detalles.
| Ver: el empleado lista/filtra y el cliente ve solo los suyos.
| Modificar: el empleado marca realizado o cancela un pedido pendiente.
| Eliminar: el empleado da de baja lógica un pedido finalizado.
| Se incluyen además los casos de permiso y validación clave.
*/

// ------------------------------------------------------------------- CREAR

test('CRUD pedidos - crear: el cliente confirma un pedido y queda pendiente con sus detalles', function () {
    $cliente = User::factory()->create(['role_id' => 1]);
    $meal = Meal::factory()->create();

    $this->actingAs($cliente)
        ->post(route('cliente.pedidos.store'), [
            'items' => [['meal_id' => $meal->id, 'quantity' => 2]],
        ])
        ->assertRedirect(route('cliente.pedidos.index'));

    $order = Order::where('user_id', $cliente->id)->first();

    expect($order)->not->toBeNull()
        ->and($order->status)->toBe(Order::STATUS_PENDIENTE);

    $this->assertDatabaseHas('order_details', [
        'order_id' => $order->id,
        'meal_id' => $meal->id,
        'quantity' => 2,
    ]);
});

test('CRUD pedidos - crear: no se puede confirmar un pedido con el carrito vacío', function () {
    $cliente = User::factory()->create(['role_id' => 1]);

    $this->actingAs($cliente)
        ->post(route('cliente.pedidos.store'), ['items' => []])
        ->assertSessionHasErrors('items');
});

test('CRUD pedidos - crear: no se puede confirmar un pedido con una comida inexistente', function () {
    $cliente = User::factory()->create(['role_id' => 1]);

    $this->actingAs($cliente)
        ->post(route('cliente.pedidos.store'), [
            'items' => [['meal_id' => 9999, 'quantity' => 1]],
        ])
        ->assertSessionHasErrors('items.0.meal_id');
});

test('CRUD pedidos - crear: un invitado es redirigido al login', function () {
    $this->post(route('cliente.pedidos.store'), [
        'items' => [['meal_id' => 1, 'quantity' => 1]],
    ])->assertRedirect(route('login'));
});

// --------------------------------------------------------------------- VER

test('CRUD pedidos - ver: el empleado ve el listado de pedidos', function () {
    $cliente = User::factory()->create(['role_id' => 1]);
    $empleado = User::factory()->create(['role_id' => 3]);
    $order = Order::factory()->create(['user_id' => $cliente->id]);
    OrderDetail::factory()->create(['order_id' => $order->id]);

    $this->actingAs($empleado)
        ->get(route('empleado.pedidos.index'))
        ->assertOk()
        ->assertViewHas('orders');
});

test('CRUD pedidos - ver: el cliente ve únicamente sus propios pedidos', function () {
    $cliente = User::factory()->create(['role_id' => 1]);
    $otro = User::factory()->create(['role_id' => 1]);
    $propio = Order::factory()->create(['user_id' => $cliente->id]);
    Order::factory()->create(['user_id' => $otro->id]);

    $this->actingAs($cliente)
        ->get(route('cliente.pedidos.index'))
        ->assertOk()
        ->assertViewHas('orders', fn ($orders) => $orders->count() === 1
            && $orders->first()->id === $propio->id);
});

test('CRUD pedidos - ver: el filtro por estado se respeta en el listado', function () {
    $empleado = User::factory()->create(['role_id' => 3]);
    Order::factory()->create(['status' => Order::STATUS_PENDIENTE]);
    Order::factory()->create(['status' => Order::STATUS_REALIZADO]);

    $this->actingAs($empleado)
        ->get(route('empleado.pedidos.index', ['estado' => Order::STATUS_REALIZADO]))
        ->assertOk()
        ->assertViewHas('orders', fn ($orders) => $orders->total() === 1
            && $orders->first()->status === Order::STATUS_REALIZADO);
});

test('CRUD pedidos - ver: un estado de filtro inválido cae a pendiente', function () {
    $empleado = User::factory()->create(['role_id' => 3]);

    $this->actingAs($empleado)
        ->get(route('empleado.pedidos.index', ['estado' => 'enviado']))
        ->assertOk()
        ->assertViewHas('status', Order::STATUS_PENDIENTE);
});

test('CRUD pedidos - ver: el auto-refresh devuelve firma, contadores y html', function () {
    $cliente = User::factory()->create(['role_id' => 1]);
    $empleado = User::factory()->create(['role_id' => 3]);
    $order = Order::factory()->create(['user_id' => $cliente->id]);
    OrderDetail::factory()->create(['order_id' => $order->id]);

    $response = $this->actingAs($empleado)
        ->getJson(route('empleado.pedidos.actualizar'))
        ->assertOk()
        ->assertJsonStructure(['signature', 'counts', 'html'])
        ->assertJsonPath('counts.pendiente', 1);

    expect($response->json('signature'))->toBeString()->not->toBeEmpty()
        ->and($response->json('html'))->toContain((string) $order->id);
});

test('CRUD pedidos - ver: un invitado es redirigido al login', function () {
    $this->get(route('empleado.pedidos.index'))->assertRedirect(route('login'));
});

test('CRUD pedidos - ver: un cliente no puede acceder al área de empleados', function () {
    $cliente = User::factory()->create(['role_id' => 1]);

    $this->actingAs($cliente)
        ->get(route('empleado.pedidos.index'))
        ->assertForbidden();
});

// --------------------------------------------------------------- MODIFICAR

test('CRUD pedidos - modificar: el empleado marca un pedido pendiente como realizado', function () {
    $empleado = User::factory()->create(['role_id' => 3]);
    $order = Order::factory()->create(['status' => Order::STATUS_PENDIENTE]);

    $this->actingAs($empleado)
        ->post(route('empleado.pedidos.realizar', $order), ['status' => Order::STATUS_REALIZADO])
        ->assertRedirect();

    expect($order->fresh()->status)->toBe(Order::STATUS_REALIZADO);
});

test('CRUD pedidos - modificar: el empleado cancela un pedido pendiente', function () {
    $empleado = User::factory()->create(['role_id' => 3]);
    $order = Order::factory()->create(['status' => Order::STATUS_PENDIENTE]);

    $this->actingAs($empleado)
        ->post(route('empleado.pedidos.cancelar', $order), ['status' => Order::STATUS_CANCELADO])
        ->assertRedirect();

    expect($order->fresh()->status)->toBe(Order::STATUS_CANCELADO);
});

test('CRUD pedidos - modificar: no se puede modificar un pedido ya finalizado', function () {
    $empleado = User::factory()->create(['role_id' => 3]);
    $order = Order::factory()->create(['status' => Order::STATUS_REALIZADO]);

    $this->actingAs($empleado)
        ->post(route('empleado.pedidos.cancelar', $order), ['status' => Order::STATUS_CANCELADO])
        ->assertRedirect();

    expect($order->fresh()->status)->toBe(Order::STATUS_REALIZADO);
});

test('CRUD pedidos - modificar: no se puede cambiar el estado a un valor inválido', function () {
    $empleado = User::factory()->create(['role_id' => 3]);
    $order = Order::factory()->create(['status' => Order::STATUS_PENDIENTE]);

    $this->actingAs($empleado)
        ->post(route('empleado.pedidos.realizar', $order), ['status' => 'enviado'])
        ->assertSessionHasErrors('status');

    expect($order->fresh()->status)->toBe(Order::STATUS_PENDIENTE);
});

test('CRUD pedidos - modificar: un cliente no puede modificar pedidos', function () {
    $cliente = User::factory()->create(['role_id' => 1]);
    $order = Order::factory()->create(['status' => Order::STATUS_PENDIENTE]);

    $this->actingAs($cliente)
        ->post(route('empleado.pedidos.realizar', $order), ['status' => Order::STATUS_REALIZADO])
        ->assertForbidden();
});

test('CRUD pedidos - modificar: un invitado es redirigido al login', function () {
    $order = Order::factory()->create(['status' => Order::STATUS_PENDIENTE]);

    $this->post(route('empleado.pedidos.realizar', $order), ['status' => Order::STATUS_REALIZADO])
        ->assertRedirect(route('login'));
});

// ---------------------------------------------------------------- ELIMINAR

test('CRUD pedidos - eliminar: el empleado da de baja lógica un pedido finalizado', function () {
    $empleado = User::factory()->create(['role_id' => 3]);
    $order = Order::factory()->create(['status' => Order::STATUS_REALIZADO]);

    $this->actingAs($empleado)
        ->delete(route('empleado.pedidos.destroy', $order))
        ->assertRedirect();

    expect(Order::find($order->id))->toBeNull()
        ->and(Order::withTrashed()->find($order->id))->not->toBeNull()
        ->and(Order::withTrashed()->find($order->id)->trashed())->toBeTrue();
});

test('CRUD pedidos - eliminar: no se puede eliminar un pedido pendiente', function () {
    $empleado = User::factory()->create(['role_id' => 3]);
    $order = Order::factory()->create(['status' => Order::STATUS_PENDIENTE]);

    $this->actingAs($empleado)
        ->delete(route('empleado.pedidos.destroy', $order))
        ->assertRedirect();

    expect(Order::find($order->id))->not->toBeNull();
});

test('CRUD pedidos - eliminar: un pedido dado de baja no aparece en el listado', function () {
    $empleado = User::factory()->create(['role_id' => 3]);
    $order = Order::factory()->create(['status' => Order::STATUS_REALIZADO]);
    $order->delete();

    $this->actingAs($empleado)
        ->get(route('empleado.pedidos.index', ['estado' => Order::STATUS_REALIZADO]))
        ->assertOk()
        ->assertViewHas('orders', fn ($orders) => $orders->total() === 0);
});

test('CRUD pedidos - eliminar: un cliente no puede eliminar pedidos', function () {
    $cliente = User::factory()->create(['role_id' => 1]);
    $order = Order::factory()->create(['status' => Order::STATUS_REALIZADO]);

    $this->actingAs($cliente)
        ->delete(route('empleado.pedidos.destroy', $order))
        ->assertForbidden();
});

test('CRUD pedidos - eliminar: un invitado es redirigido al login', function () {
    $order = Order::factory()->create(['status' => Order::STATUS_REALIZADO]);

    $this->delete(route('empleado.pedidos.destroy', $order))
        ->assertRedirect(route('login'));
});
