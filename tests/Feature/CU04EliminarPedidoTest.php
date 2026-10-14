<?php

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
| CU04 - Eliminar pedido
|------------------------------------------------------------------------------
| Datos reales: el empleado da de baja lógica un pedido finalizado.
| Datos falsos: pedido pendiente, actor sin permiso o invitado.
*/

test('CU04 - un empleado da de baja lógica un pedido finalizado', function () {
    $empleado = User::factory()->create(['role_id' => 3]);
    $order = Order::factory()->create(['status' => Order::STATUS_REALIZADO]);

    $this->actingAs($empleado)
        ->delete(route('empleado.pedidos.destroy', $order))
        ->assertRedirect();

    expect(Order::find($order->id))->toBeNull()
        ->and(Order::withTrashed()->find($order->id))->not->toBeNull()
        ->and(Order::withTrashed()->find($order->id)->trashed())->toBeTrue();
});

test('CU04 - un empleado no puede eliminar un pedido pendiente', function () {
    $empleado = User::factory()->create(['role_id' => 3]);
    $order = Order::factory()->create(['status' => Order::STATUS_PENDIENTE]);

    $this->actingAs($empleado)
        ->delete(route('empleado.pedidos.destroy', $order))
        ->assertRedirect();

    expect(Order::find($order->id))->not->toBeNull();
});

test('CU04 - un pedido dado de baja no aparece en el listado del empleado', function () {
    $empleado = User::factory()->create(['role_id' => 3]);
    $order = Order::factory()->create(['status' => Order::STATUS_REALIZADO]);
    $order->delete();

    $this->actingAs($empleado)
        ->get(route('empleado.pedidos.index', ['estado' => Order::STATUS_REALIZADO]))
        ->assertOk()
        ->assertViewHas('orders', fn ($orders) => $orders->total() === 0);
});

test('CU04 - un cliente no puede eliminar pedidos', function () {
    $cliente = User::factory()->create(['role_id' => 1]);
    $order = Order::factory()->create(['status' => Order::STATUS_REALIZADO]);

    $this->actingAs($cliente)
        ->delete(route('empleado.pedidos.destroy', $order))
        ->assertForbidden();
});

test('CU04 - un invitado es redirigido al login al eliminar un pedido', function () {
    $order = Order::factory()->create(['status' => Order::STATUS_REALIZADO]);

    $this->delete(route('empleado.pedidos.destroy', $order))
        ->assertRedirect(route('login'));
});
