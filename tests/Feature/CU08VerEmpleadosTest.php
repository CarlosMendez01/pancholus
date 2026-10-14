<?php

use App\Models\User;
use Database\Seeders\RolSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolSeeder::class);
});

/*
|------------------------------------------------------------------------------
| CU08 - Ver empleados
|------------------------------------------------------------------------------
| Datos reales: se listan los usuarios con rol de empleado (3 y 4) y status alta.
| Datos falsos: clientes, administradores o empleados dados de baja no aparecen.
*/

test('CU08 - el listado muestra los empleados activos', function () {
    $admin = User::factory()->create(['role_id' => 2]);
    $empleado = User::factory()->create(['role_id' => 3]);
    $encargado = User::factory()->create(['role_id' => 4]);

    $this->actingAs($admin)
        ->get(route('admin.empleados.index'))
        ->assertOk()
        ->assertViewHas('users', function ($users) use ($empleado, $encargado) {
            return $users->contains('id', $empleado->id)
                && $users->contains('id', $encargado->id);
        });
});

test('CU08 - el listado no muestra clientes ni administradores', function () {
    $admin = User::factory()->create(['role_id' => 2]);
    $cliente = User::factory()->create(['role_id' => 1]);

    $this->actingAs($admin)
        ->get(route('admin.empleados.index'))
        ->assertOk()
        ->assertViewHas('users', function ($users) use ($cliente, $admin) {
            return ! $users->contains('id', $cliente->id)
                && ! $users->contains('id', $admin->id);
        });
});

test('CU08 - el listado no muestra empleados dados de baja', function () {
    $admin = User::factory()->create(['role_id' => 2]);
    $empleado = User::factory()->create(['role_id' => 3, 'status' => 'baja']);

    $this->actingAs($admin)
        ->get(route('admin.empleados.index'))
        ->assertOk()
        ->assertViewHas('users', fn ($users) => ! $users->contains('id', $empleado->id));
});

test('CU08 - un invitado es redirigido al login', function () {
    $this->get(route('admin.empleados.index'))->assertRedirect(route('login'));
});

test('CU08 - un cliente no puede ver el listado de empleados', function () {
    $cliente = User::factory()->create(['role_id' => 1]);

    $this->actingAs($cliente)
        ->get(route('admin.empleados.index'))
        ->assertForbidden();
});

test('CU08 - un empleado no puede ver el listado de empleados', function () {
    $empleado = User::factory()->create(['role_id' => 3]);

    $this->actingAs($empleado)
        ->get(route('admin.empleados.index'))
        ->assertForbidden();
});
