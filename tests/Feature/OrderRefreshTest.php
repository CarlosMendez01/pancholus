<?php

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Database\Seeders\RolSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolSeeder::class);
});

test('un invitado es redirigido al login al pedir la actualización', function () {
    $this->get(route('empleado.pedidos.actualizar'))
        ->assertRedirect(route('login'));
});

test('un cliente no puede acceder a la actualización de pedidos', function () {
    $cliente = User::factory()->create(['role_id' => 1]);

    $this->actingAs($cliente)
        ->get(route('empleado.pedidos.actualizar'))
        ->assertForbidden();
});

test('un empleado recibe la firma, los contadores y el html de la lista', function () {
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

test('la firma cambia al crear un pedido nuevo', function () {
    $cliente = User::factory()->create(['role_id' => 1]);
    $empleado = User::factory()->create(['role_id' => 3]);

    $before = $this->actingAs($empleado)
        ->getJson(route('empleado.pedidos.actualizar'))
        ->json('signature');

    Order::factory()->create(['user_id' => $cliente->id]);

    $after = $this->actingAs($empleado)
        ->getJson(route('empleado.pedidos.actualizar'))
        ->json('signature');

    expect($after)->not->toBe($before);
});

test('la firma cambia al actualizar el estado de un pedido', function () {
    $empleado = User::factory()->create(['role_id' => 3]);
    $order = Order::factory()->create();

    $before = $this->actingAs($empleado)
        ->getJson(route('empleado.pedidos.actualizar'))
        ->json('signature');

    $order->update(['status' => Order::STATUS_REALIZADO]);

    $after = $this->actingAs($empleado)
        ->getJson(route('empleado.pedidos.actualizar'))
        ->json('signature');

    expect($after)->not->toBe($before);
});

test('el filtro por estado se respeta en la actualización', function () {
    $empleado = User::factory()->create(['role_id' => 3]);
    Order::factory()->create(['status' => Order::STATUS_PENDIENTE]);
    Order::factory()->create(['status' => Order::STATUS_REALIZADO]);

    $this->actingAs($empleado)
        ->getJson(route('empleado.pedidos.actualizar', ['estado' => Order::STATUS_REALIZADO]))
        ->assertOk()
        ->assertJsonPath('counts.pendiente', 1)
        ->assertJsonPath('counts.realizado', 1);
});
