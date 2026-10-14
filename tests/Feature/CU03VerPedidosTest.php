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

/*
|------------------------------------------------------------------------------
| CU03 - Ver pedidos
|------------------------------------------------------------------------------
| Datos reales: el empleado ve y filtra los pedidos; el cliente ve los propios.
| Datos falsos: invitado, rol sin permiso o filtro inválido.
*/

test('CU03 - un empleado ve el listado de pedidos', function () {
    $cliente = User::factory()->create(['role_id' => 1]);
    $empleado = User::factory()->create(['role_id' => 3]);
    $order = Order::factory()->create(['user_id' => $cliente->id]);
    OrderDetail::factory()->create(['order_id' => $order->id]);

    $this->actingAs($empleado)
        ->get(route('empleado.pedidos.index'))
        ->assertOk()
        ->assertViewHas('orders');
});

test('CU03 - un cliente ve únicamente sus propios pedidos', function () {
    $cliente = User::factory()->create(['role_id' => 1]);
    $otro = User::factory()->create(['role_id' => 1]);
    $propio = Order::factory()->create(['user_id' => $cliente->id]);
    Order::factory()->create(['user_id' => $otro->id]);

    $this->actingAs($cliente)
        ->get(route('cliente.pedidos.index'))
        ->assertOk()
        ->assertViewHas('orders', function ($orders) use ($propio) {
            return $orders->count() === 1 && $orders->first()->id === $propio->id;
        });
});

test('CU03 - el auto-refresh devuelve firma, contadores y html', function () {
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

test('CU03 - la firma cambia al crear un pedido nuevo', function () {
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

test('CU03 - la firma cambia al actualizar el estado de un pedido', function () {
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

test('CU03 - el filtro por estado se respeta en el listado', function () {
    $empleado = User::factory()->create(['role_id' => 3]);
    Order::factory()->create(['status' => Order::STATUS_PENDIENTE]);
    Order::factory()->create(['status' => Order::STATUS_REALIZADO]);

    $this->actingAs($empleado)
        ->get(route('empleado.pedidos.index', ['estado' => Order::STATUS_REALIZADO]))
        ->assertOk()
        ->assertViewHas('orders', fn ($orders) => $orders->total() === 1
            && $orders->first()->status === Order::STATUS_REALIZADO);
});

test('CU03 - un estado de filtro inválido cae a pendiente', function () {
    $empleado = User::factory()->create(['role_id' => 3]);

    $this->actingAs($empleado)
        ->get(route('empleado.pedidos.index', ['estado' => 'enviado']))
        ->assertOk()
        ->assertViewHas('status', Order::STATUS_PENDIENTE);
});

test('CU03 - un invitado es redirigido al login', function () {
    $this->get(route('empleado.pedidos.index'))->assertRedirect(route('login'));
});

test('CU03 - un cliente no puede acceder al área de empleados', function () {
    $cliente = User::factory()->create(['role_id' => 1]);

    $this->actingAs($cliente)
        ->get(route('empleado.pedidos.index'))
        ->assertForbidden();
});
