<?php

use App\Models\Meal;
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
| CU01 - Cargar pedido
|------------------------------------------------------------------------------
| Datos reales: el cliente arma el carrito y confirma el pedido.
| Datos falsos: invitado, carrito vacío, comida inexistente o cantidad inválida.
*/

test('CU01 - el menú y el carrito requieren una sesión de cliente', function () {
    $this->get(route('cliente.menu'))->assertRedirect(route('login'));
    $this->get(route('cliente.carrito.index'))->assertRedirect(route('login'));
});

test('CU01 - el cliente ve en el carrito lo que agregó', function () {
    $cliente = User::factory()->create(['role_id' => 1]);
    $meal = Meal::factory()->create();

    $this->actingAs($cliente)
        ->post(route('cliente.carrito.agregar'), ['meal_id' => $meal->id, 'quantity' => 2]);

    $this->actingAs($cliente)
        ->get(route('cliente.carrito.index'))
        ->assertOk()
        ->assertViewHas('items', function ($items) use ($meal) {
            return count($items) === 1
                && $items[0]['id'] === $meal->id
                && $items[0]['quantity'] === 2;
        });
});

test('CU01 - el cliente autenticado ve el menú de comidas', function () {
    $cliente = User::factory()->create(['role_id' => 1]);
    Meal::factory()->create();

    $this->actingAs($cliente)
        ->get(route('cliente.menu'))
        ->assertOk()
        ->assertViewHas('comidas');
});

test('CU01 - un cliente agrega una comida al carrito con la cantidad indicada', function () {
    $cliente = User::factory()->create(['role_id' => 1]);
    $meal = Meal::factory()->create();

    $this->actingAs($cliente)
        ->post(route('cliente.carrito.agregar'), [
            'meal_id' => $meal->id,
            'quantity' => 2,
        ])
        ->assertRedirect();

    expect(session('cart'))->toBe([$meal->id => 2]);
});

test('CU01 - agregar la misma comida dos veces acumula la cantidad', function () {
    $cliente = User::factory()->create(['role_id' => 1]);
    $meal = Meal::factory()->create();

    $this->actingAs($cliente)->post(route('cliente.carrito.agregar'), ['meal_id' => $meal->id, 'quantity' => 2]);
    $this->actingAs($cliente)->post(route('cliente.carrito.agregar'), ['meal_id' => $meal->id, 'quantity' => 3]);

    expect(session('cart'))->toBe([$meal->id => 5]);
});

test('CU01 - un cliente quita una comida del carrito', function () {
    $cliente = User::factory()->create(['role_id' => 1]);
    $meal = Meal::factory()->create();

    $this->actingAs($cliente)->post(route('cliente.carrito.agregar'), ['meal_id' => $meal->id, 'quantity' => 2]);
    $this->actingAs($cliente)->post(route('cliente.carrito.quitar'), ['meal_id' => $meal->id]);

    expect(session('cart'))->toBe([]);
});

test('CU01 - un cliente confirma el pedido y queda pendiente con sus detalles', function () {
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

test('CU01 - un invitado es redirigido al login al confirmar el pedido', function () {
    $this->post(route('cliente.pedidos.store'), [
        'items' => [['meal_id' => 1, 'quantity' => 1]],
    ])->assertRedirect(route('login'));
});

test('CU01 - no se puede confirmar un pedido con el carrito vacío', function () {
    $cliente = User::factory()->create(['role_id' => 1]);

    $this->actingAs($cliente)
        ->post(route('cliente.pedidos.store'), ['items' => []])
        ->assertSessionHasErrors('items');
});

test('CU01 - no se puede confirmar un pedido con una comida inexistente', function () {
    $cliente = User::factory()->create(['role_id' => 1]);

    $this->actingAs($cliente)
        ->post(route('cliente.pedidos.store'), [
            'items' => [['meal_id' => 9999, 'quantity' => 1]],
        ])->assertSessionHasErrors('items.0.meal_id');
});

test('CU01 - no se puede agregar al carrito una cantidad fuera del rango 1-99', function () {
    $cliente = User::factory()->create(['role_id' => 1]);
    $meal = Meal::factory()->create();

    $this->actingAs($cliente)
        ->post(route('cliente.carrito.agregar'), ['meal_id' => $meal->id, 'quantity' => 0])
        ->assertSessionHasErrors('quantity');

    $this->actingAs($cliente)
        ->post(route('cliente.carrito.agregar'), ['meal_id' => $meal->id, 'quantity' => 100])
        ->assertSessionHasErrors('quantity');
});
