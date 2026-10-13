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

test('un invitado es redirigido al login al intentar cargar un pedido', function () {
    $this->post(route('cliente.pedidos.store'), [
        'items' => [['meal_id' => 1, 'quantity' => 1]],
    ])->assertRedirect(route('login'));
});

test('un cliente autenticado crea un pedido en estado pendiente', function () {
    $cliente = User::factory()->create(['role_id' => 1]);
    $meal = Meal::factory()->create();

    $this->actingAs($cliente)->post(route('cliente.pedidos.store'), [
        'items' => [
            ['meal_id' => $meal->id, 'quantity' => 2],
        ],
    ])->assertRedirect(route('cliente.pedidos.index'));

    $order = Order::where('user_id', $cliente->id)->first();

    expect($order)->not->toBeNull()
        ->and($order->status)->toBe(Order::STATUS_PENDIENTE);

    $this->assertDatabaseHas('order_details', [
        'order_id' => $order->id,
        'meal_id' => $meal->id,
        'quantity' => 2,
    ]);
});

test('un cliente no puede crear un pedido sin items', function () {
    $cliente = User::factory()->create(['role_id' => 1]);

    $this->actingAs($cliente)
        ->post(route('cliente.pedidos.store'), ['items' => []])
        ->assertSessionHasErrors('items');
});

test('un cliente no puede crear un pedido con una comida inexistente', function () {
    $cliente = User::factory()->create(['role_id' => 1]);

    $this->actingAs($cliente)
        ->post(route('cliente.pedidos.store'), [
            'items' => [['meal_id' => 9999, 'quantity' => 1]],
        ])->assertSessionHasErrors('items.0.meal_id');
});

test('un cliente no puede acceder al área de empleados', function () {
    $cliente = User::factory()->create(['role_id' => 1]);

    $this->actingAs($cliente)
        ->get(route('empleado.pedidos.index'))
        ->assertForbidden();
});

test('un empleado autenticado ve los pedidos pendientes', function () {
    $cliente = User::factory()->create(['role_id' => 1]);
    $empleado = User::factory()->create(['role_id' => 3]);
    $order = Order::factory()->create(['user_id' => $cliente->id]);
    OrderDetail::factory()->create(['order_id' => $order->id]);

    $this->actingAs($empleado)
        ->get(route('empleado.pedidos.index'))
        ->assertOk()
        ->assertViewHas('orders');
});

test('un empleado puede marcar un pedido pendiente como realizado', function () {
    $empleado = User::factory()->create(['role_id' => 3]);
    $order = Order::factory()->create();

    $this->actingAs($empleado)
        ->post(route('empleado.pedidos.realizar', $order), ['status' => Order::STATUS_REALIZADO])
        ->assertRedirect();

    expect($order->fresh()->status)->toBe(Order::STATUS_REALIZADO);
});

test('un empleado puede cancelar un pedido pendiente', function () {
    $empleado = User::factory()->create(['role_id' => 3]);
    $order = Order::factory()->create();

    $this->actingAs($empleado)
        ->post(route('empleado.pedidos.cancelar', $order), ['status' => Order::STATUS_CANCELADO])
        ->assertRedirect();

    expect($order->fresh()->status)->toBe(Order::STATUS_CANCELADO);
});

test('no se puede modificar el estado de un pedido ya finalizado', function () {
    $empleado = User::factory()->create(['role_id' => 3]);
    $order = Order::factory()->create(['status' => Order::STATUS_REALIZADO]);

    $this->actingAs($empleado)
        ->post(route('empleado.pedidos.cancelar', $order), ['status' => Order::STATUS_CANCELADO])
        ->assertRedirect();

    expect($order->fresh()->status)->toBe(Order::STATUS_REALIZADO);
});

test('un empleado no puede cambiar el estado a un valor inválido', function () {
    $empleado = User::factory()->create(['role_id' => 3]);
    $order = Order::factory()->create();

    $this->actingAs($empleado)
        ->post(route('empleado.pedidos.realizar', $order), ['status' => 'enviado'])
        ->assertSessionHasErrors('status');

    expect($order->fresh()->status)->toBe(Order::STATUS_PENDIENTE);
});
