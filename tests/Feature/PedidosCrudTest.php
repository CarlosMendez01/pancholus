<?php

use App\Models\FoodTruck;
use App\Models\Meal;
use App\Models\Order;
use App\Models\User;
use Database\Seeders\FilterSeeder;
use Database\Seeders\RolSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed([
        RolSeeder::class,
        FilterSeeder::class,
    ]);
});

test('(CU01) Cargar pedido: Permite a un Cliente confirmar un pedido y queda pendiente con sus detalles', function () {

    $cliente = User::factory()->create(['role_id' => 1]);
    $meal = Meal::factory()->create(['filter_id' => 2]);
    $foodTruck = FoodTruck::factory()->create();

    $this->actingAs($cliente)->withSession([
        'foodtruck' => $foodTruck->id,
        'cart' => [
            $meal->id => [
                'meal_id' => $meal->id,
                'meal_quantity' => 2,
            ],
        ],
    ]);

    $this->post(route('cliente.pedidos.store'))
        ->assertRedirect(route('cliente.menu'));

    $order = Order::where('user_id', $cliente->id)->first();

    expect($order)->not->toBeNull()
        ->and($order->status)->toBe(Order::STATUS_PENDIENTE)
        ->and($order->foodtruck_id)->toBe($foodTruck->id);

    $this->assertDatabaseHas('order_details', [
        'order_id' => $order->id,
        'meal_id' => $meal->id,
        'quantity' => 2,
    ]);
});

test('(CU03) Ver pedidos: Permite a un Empleado visualizar los pedidos registrados en el sistema.', function () {
    $cliente = User::factory()->create(['role_id' => 1]);
    $empleado = User::factory()->create(['role_id' => 3]);
    $order = Order::factory()->create(['user_id' => $cliente->id]);

    $this->actingAs($empleado)
        ->get(route('empleado.inicio'))
        ->assertOk()
        ->assertSee($order->id);
});

test('(CU02) Modificar pedido: Permite a un Empleado realizar un pedido pendiente.', function () {
    $cliente = User::factory()->create(['role_id' => 1]);
    $empleado = User::factory()->create(['role_id' => 3]);
    $order = Order::factory()->create([
        'user_id' => $cliente->id,
        'status' => Order::STATUS_PENDIENTE,
    ]);

    $this->from(route('empleado.pedidos.index'))
        ->actingAs($empleado)
        ->post(route('empleado.pedidos.realizar', $order), [
            'status' => Order::STATUS_REALIZADO,
        ])
        ->assertRedirect(route('empleado.pedidos.index'));

    expect($order->fresh()->status)->toBe(Order::STATUS_REALIZADO);
});

test('(CU02) Modificar pedido: Permite a un Empleado cancelar un pedido pendiente.', function () {
    $cliente = User::factory()->create(['role_id' => 1]);
    $empleado = User::factory()->create(['role_id' => 3]);
    $order = Order::factory()->create([
        'user_id' => $cliente->id,
        'status' => Order::STATUS_PENDIENTE,
    ]);

    $this->from(route('empleado.pedidos.index'))
        ->actingAs($empleado)
        ->post(route('empleado.pedidos.cancelar', $order), [
            'status' => Order::STATUS_CANCELADO,
        ])
        ->assertRedirect(route('empleado.pedidos.index'));

    expect($order->fresh()->status)->toBe(Order::STATUS_CANCELADO);
});

test('(CU04) Eliminar pedido: Permite a un Empleado eliminar  un pedido cuyo estado sea “Realizado”/“Cancelado”.', function () {
    $cliente = User::factory()->create(['role_id' => 1]);
    $empleado = User::factory()->create(['role_id' => 3]);
    $order = Order::factory()->create([
        'user_id' => $cliente->id,
        'status' => Order::STATUS_REALIZADO,
    ]);

    $this->from(route('empleado.pedidos.index'))
        ->actingAs($empleado)
        ->delete(route('empleado.pedidos.destroy', $order))
        ->assertRedirect(route('empleado.pedidos.index'));

    $this->assertSoftDeleted('orders', [
        'id' => $order->id,
    ]);
});
