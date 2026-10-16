<?php

use App\Models\Rol;
use App\Models\User;
use Database\Seeders\RolSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolSeeder::class);
});

/*
|------------------------------------------------------------------------------
| Configuración - Gestión de roles
|------------------------------------------------------------------------------
| El administrador crea, edita y elimina roles personalizados. Los roles del
| sistema (1 = cliente, 2 = administrador) no se pueden editar ni eliminar, y
| no se permiten nombres vacíos ni repetidos.
*/

function usuarioAdmin(): User
{
    return User::factory()->create(['role_id' => 2]);
}

test('un administrador ve la lista de roles sin los roles del sistema', function () {
    $this->actingAs(usuarioAdmin())
        ->get(route('admin.roles.index'))
        ->assertOk()
        ->assertViewHas('roles', fn ($roles) => $roles->contains('role', 'empleado')
            && ! $roles->contains('id', 1)
            && ! $roles->contains('id', 2));
});

test('un administrador crea un rol nuevo', function () {
    $this->actingAs(usuarioAdmin())
        ->post(route('admin.roles.store'), ['nuevo_rol' => 'supervisor'])
        ->assertRedirect(route('admin.roles.index'));

    $this->assertDatabaseHas('rols', ['role' => 'supervisor']);
});

test('no se puede crear un rol vacío', function () {
    $this->actingAs(usuarioAdmin())
        ->post(route('admin.roles.store'), ['nuevo_rol' => ''])
        ->assertSessionHasErrors('nuevo_rol');
});

test('no se puede crear un rol con un nombre repetido', function () {
    $this->actingAs(usuarioAdmin())
        ->post(route('admin.roles.store'), ['nuevo_rol' => 'empleado'])
        ->assertSessionHasErrors('nuevo_rol');
});

test('un administrador ve el formulario de edición de un rol', function () {
    $rol = Rol::create(['role' => 'supervisor']);

    $this->actingAs(usuarioAdmin())
        ->get(route('admin.roles.edit', $rol))
        ->assertOk()
        ->assertViewHas('rol', fn ($visto) => $visto->id === $rol->id);
});

test('un administrador actualiza el nombre de un rol', function () {
    $rol = Rol::create(['role' => 'supervisor']);

    $this->actingAs(usuarioAdmin())
        ->post(route('admin.roles.update', $rol), ['rol_actualizado' => 'encargado'])
        ->assertRedirect(route('admin.roles.index'));

    $this->assertDatabaseHas('rols', ['id' => $rol->id, 'role' => 'encargado']);
});

test('mantener el nombre del propio rol no da error de duplicado', function () {
    $rol = Rol::create(['role' => 'supervisor']);

    $this->actingAs(usuarioAdmin())
        ->post(route('admin.roles.update', $rol), ['rol_actualizado' => 'supervisor'])
        ->assertSessionHasNoErrors();
});

test('no se puede actualizar un rol con un nombre vacío', function () {
    $rol = Rol::create(['role' => 'supervisor']);

    $this->actingAs(usuarioAdmin())
        ->post(route('admin.roles.update', $rol), ['rol_actualizado' => ''])
        ->assertSessionHasErrors('rol_actualizado');
});

test('no se puede actualizar un rol a un nombre ya usado por otro', function () {
    Rol::create(['role' => 'supervisor']);
    $otro = Rol::create(['role' => 'encargado']);

    $this->actingAs(usuarioAdmin())
        ->post(route('admin.roles.update', $otro), ['rol_actualizado' => 'supervisor'])
        ->assertSessionHasErrors('rol_actualizado');
});

test('los roles del sistema no se pueden editar ni actualizar', function () {
    foreach ([1, 2] as $id) {
        $this->actingAs(usuarioAdmin())->get(route('admin.roles.edit', $id))->assertNotFound();

        $this->actingAs(usuarioAdmin())
            ->post(route('admin.roles.update', $id), ['rol_actualizado' => 'otro'])
            ->assertNotFound();
    }
});

test('los roles del sistema no se pueden eliminar', function () {
    foreach ([1, 2] as $id) {
        $this->actingAs(usuarioAdmin())
            ->delete(route('admin.roles.delete', $id))
            ->assertNotFound();
    }
});

test('un administrador elimina un rol sin usuarios asignados', function () {
    $rol = Rol::create(['role' => 'supervisor']);

    $this->actingAs(usuarioAdmin())
        ->delete(route('admin.roles.delete', $rol))
        ->assertRedirect(route('admin.roles.index'));

    $this->assertDatabaseMissing('rols', ['id' => $rol->id]);
});

test('no se puede eliminar un rol asignado a usuarios', function () {
    User::factory()->create(['role_id' => 4]); // encargado_deposito

    $this->actingAs(usuarioAdmin())
        ->delete(route('admin.roles.delete', 4))
        ->assertRedirect(route('admin.roles.index'))
        ->assertSessionHas('error');

    $this->assertDatabaseHas('rols', ['id' => 4]);
});

test('un invitado es redirigido al login al gestionar roles', function () {
    $this->post(route('admin.roles.store'), ['nuevo_rol' => 'supervisor'])
        ->assertRedirect(route('login'));

    $this->assertDatabaseMissing('rols', ['role' => 'supervisor']);
});

test('un usuario que no es administrador no puede gestionar roles', function () {
    $empleado = User::factory()->create(['role_id' => 3]);

    $this->actingAs($empleado)
        ->post(route('admin.roles.store'), ['nuevo_rol' => 'supervisor'])
        ->assertForbidden();
});
