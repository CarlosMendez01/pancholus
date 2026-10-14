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
| CU02 - Modificar pedido
|------------------------------------------------------------------------------
| Datos reales: el empleado marca como realizado o cancela un pedido pendiente.
| Datos falsos: pedido ya finalizado, estado inválido, actor sin permiso.
*/

test('CU02 - un empleado marca un pedido pendiente como realizado', function () {
    $empleado = User::factory()->create(['role_id' => 3]);
    $order = Order::factory()->create(['status' => Order::STATUS_PENDIENTE]);

    $this->actingAs($empleado)
        ->post(route('empleado.pedidos.realizar', $order), ['status' => Order::STATUS_REALIZADO])
        ->assertRedirect();

    expect($order->fresh()->status)->toBe(Order::STATUS_REALIZADO);
});

test('CU02 - un empleado cancela un pedido pendiente', function () {
    $empleado = User::factory()->create(['role_id' => 3]);
    $order = Order::factory()->create(['status' => Order::STATUS_PENDIENTE]);

    $this->actingAs($empleado)
        ->post(route('empleado.pedidos.cancelar', $order), ['status' => Order::STATUS_CANCELADO])
        ->assertRedirect();

    expect($order->fresh()->status)->toBe(Order::STATUS_CANCELADO);
});

test('CU02 - no se puede modificar el estado de un pedido ya finalizado', function () {
    $empleado = User::factory()->create(['role_id' => 3]);
    $order = Order::factory()->create(['status' => Order::STATUS_REALIZADO]);

    $this->actingAs($empleado)
        ->post(route('empleado.pedidos.cancelar', $order), ['status' => Order::STATUS_CANCELADO])
        ->assertRedirect();

    expect($order->fresh()->status)->toBe(Order::STATUS_REALIZADO);
});

test('CU02 - no se puede cambiar el estado a un valor inválido', function () {
    $empleado = User::factory()->create(['role_id' => 3]);
    $order = Order::factory()->create(['status' => Order::STATUS_PENDIENTE]);

    $this->actingAs($empleado)
        ->post(route('empleado.pedidos.realizar', $order), ['status' => 'enviado'])
        ->assertSessionHasErrors('status');

    expect($order->fresh()->status)->toBe(Order::STATUS_PENDIENTE);
});

test('CU02 - un cliente no puede modificar pedidos', function () {
    $cliente = User::factory()->create(['role_id' => 1]);
    $order = Order::factory()->create(['status' => Order::STATUS_PENDIENTE]);

    $this->actingAs($cliente)
        ->post(route('empleado.pedidos.realizar', $order), ['status' => Order::STATUS_REALIZADO])
        ->assertForbidden();
});

test('CU02 - un invitado es redirigido al login al modificar un pedido', function () {
    $order = Order::factory()->create(['status' => Order::STATUS_PENDIENTE]);

    $this->post(route('empleado.pedidos.realizar', $order), ['status' => Order::STATUS_REALIZADO])
        ->assertRedirect(route('login'));
});
