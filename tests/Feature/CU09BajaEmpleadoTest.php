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
| CU09 - Dar de baja empleado
|------------------------------------------------------------------------------
| Datos reales: el administrador da de baja a un empleado (status baja).
| Datos falsos: un usuario inexistente, un usuario que no es empleado o un
| empleado ya dado de baja, que se rechazan con 404.
*/

test('CU09 - un administrador da de baja a un empleado', function () {
    $admin = User::factory()->create(['role_id' => 2]);
    $empleado = User::factory()->create(['role_id' => 3, 'status' => 'alta']);

    $this->actingAs($admin)
        ->delete(route('admin.empleados.delete', $empleado))
        ->assertRedirect(route('admin.empleados.index'));

    expect($empleado->fresh()->status)->toBe('baja');
});

test('CU09 - el empleado dado de baja desaparece del listado', function () {
    $admin = User::factory()->create(['role_id' => 2]);
    $empleado = User::factory()->create(['role_id' => 3, 'status' => 'alta']);

    $this->actingAs($admin)->delete(route('admin.empleados.delete', $empleado));

    $this->actingAs($admin)
        ->get(route('admin.empleados.index'))
        ->assertOk()
        ->assertViewHas('users', fn ($users) => ! $users->contains('id', $empleado->id));
});

test('CU09 - dar de baja un usuario inexistente devuelve 404', function () {
    $admin = User::factory()->create(['role_id' => 2]);

    $this->actingAs($admin)
        ->delete(route('admin.empleados.delete', 999999))
        ->assertNotFound();
});

test('CU09 - dar de baja a un usuario que no es un empleado activo devuelve 404', function () {
    $admin = User::factory()->create(['role_id' => 2]);
    $cliente = User::factory()->create(['role_id' => 1]);
    $baja = User::factory()->create(['role_id' => 3, 'status' => 'baja']);

    foreach ([$cliente, $baja] as $objetivo) {
        $this->actingAs($admin)
            ->delete(route('admin.empleados.delete', $objetivo))
            ->assertNotFound();
    }

    expect($cliente->fresh()->status)->toBe('alta');
});

test('CU09 - dar de baja no elimina físicamente al empleado', function () {
    $admin = User::factory()->create(['role_id' => 2]);
    $empleado = User::factory()->create(['role_id' => 3, 'status' => 'alta']);

    $this->actingAs($admin)->delete(route('admin.empleados.delete', $empleado));

    $this->assertDatabaseHas('users', [
        'id' => $empleado->id,
        'status' => 'baja',
    ]);
});

test('CU09 - un invitado es redirigido al login al dar de baja un empleado', function () {
    $empleado = User::factory()->create(['role_id' => 3, 'status' => 'alta']);

    $this->delete(route('admin.empleados.delete', $empleado))
        ->assertRedirect(route('login'));

    expect($empleado->fresh()->status)->toBe('alta');
});

test('CU09 - un usuario sin rol de administrador no puede dar de baja empleados', function () {
    $empleado = User::factory()->create(['role_id' => 3, 'status' => 'alta']);
    $otro = User::factory()->create(['role_id' => 3, 'status' => 'alta']);

    $this->actingAs($empleado)
        ->delete(route('admin.empleados.delete', $otro))
        ->assertForbidden();

    expect($otro->fresh()->status)->toBe('alta');
});
