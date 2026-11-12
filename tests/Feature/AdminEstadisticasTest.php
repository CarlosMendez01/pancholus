<?php

use App\Models\Order;
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
| Administración - Estadísticas de pedidos
|------------------------------------------------------------------------------
| El panel y su endpoint de auto-refresh devuelven el total de pedidos
| agrupados por estado.
*/

test('un administrador ve el panel de estadísticas con los totales', function () {
    Order::factory()->count(2)->create(['status' => Order::STATUS_PENDIENTE]);
    Order::factory()->create(['status' => Order::STATUS_REALIZADO]);
    Order::factory()->count(3)->create(['status' => Order::STATUS_CANCELADO]);

    $this->actingAs($this->admin)
        ->get(route('admin.statistics.index'))
        ->assertOk()
        ->assertViewHas('pending', 2)
        ->assertViewHas('completed', 1)
        ->assertViewHas('cancelled', 3)
        ->assertSee('Estadísticas de pedidos');
});

test('el endpoint de estadísticas devuelve los contadores en JSON', function () {
    Order::factory()->count(4)->create(['status' => Order::STATUS_PENDIENTE]);
    Order::factory()->count(2)->create(['status' => Order::STATUS_REALIZADO]);

    $this->actingAs($this->admin)
        ->getJson(route('admin.statistics.data'))
        ->assertOk()
        ->assertExactJson([
            'pending' => 4,
            'completed' => 2,
            'cancelled' => 0,
        ]);
});

test('las estadísticas no cuentan pedidos eliminados lógicamente', function () {
    Order::factory()->create(['status' => Order::STATUS_PENDIENTE]);
    $eliminado = Order::factory()->create(['status' => Order::STATUS_PENDIENTE]);
    $eliminado->delete();

    $this->actingAs($this->admin)
        ->getJson(route('admin.statistics.data'))
        ->assertOk()
        ->assertExactJson([
            'pending' => 1,
            'completed' => 0,
            'cancelled' => 0,
        ]);
});

test('un usuario que no es administrador no accede a las estadísticas', function () {
    $empleado = User::factory()->create(['role_id' => 3]);

    $this->actingAs($empleado)
        ->get(route('admin.statistics.index'))
        ->assertForbidden();

    $this->actingAs($empleado)
        ->getJson(route('admin.statistics.data'))
        ->assertForbidden();
});
