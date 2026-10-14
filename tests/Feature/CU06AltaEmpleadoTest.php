<?php

use App\Models\User;
use Database\Seeders\RolSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolSeeder::class);
});

/*
|------------------------------------------------------------------------------
| CU06 - Dar de alta empleado
|------------------------------------------------------------------------------
| Datos reales: el administrador registra un empleado (rol 3) con status alta.
| Datos falsos: alta con un rol que no es de empleado (cliente/administrador) o
| inexistente, que se rechaza con errores de validación.
*/

function datosEmpleado(array $overrides = []): array
{
    return array_merge([
        'empleado_nombre' => 'Ana',
        'empleado_apellido' => 'Pérez',
        'empleado_dni' => '30111222',
        'empleado_telefono' => '1122334455',
        'empleado_correo' => 'ana.perez@example.com',
        'empleado_contrasena' => 'secreto123',
        'rol_id' => 3,
    ], $overrides);
}

test('CU06 - un administrador da de alta un empleado', function () {
    $admin = User::factory()->create(['role_id' => 2]);

    $this->actingAs($admin)
        ->post(route('admin.empleados.store'), datosEmpleado())
        ->assertRedirect(route('admin.empleados.index'));

    $this->assertDatabaseHas('users', [
        'name' => 'Ana',
        'last_name' => 'Pérez',
        'dni' => '30111222',
        'email' => 'ana.perez@example.com',
        'role_id' => 3,
        'status' => 'alta',
    ]);
});

test('CU06 - la contraseña del empleado se guarda hasheada', function () {
    $admin = User::factory()->create(['role_id' => 2]);

    $this->actingAs($admin)->post(route('admin.empleados.store'), datosEmpleado());

    $empleado = User::where('dni', '30111222')->first();

    expect($empleado)->not->toBeNull()
        ->and($empleado->password)->not->toBe('secreto123')
        ->and(Hash::check('secreto123', $empleado->password))->toBeTrue();
});

test('CU06 - el empleado dado de alta aparece en el listado', function () {
    $admin = User::factory()->create(['role_id' => 2]);

    $this->actingAs($admin)->post(route('admin.empleados.store'), datosEmpleado());

    $empleado = User::where('dni', '30111222')->first();

    $this->actingAs($admin)
        ->get(route('admin.empleados.index'))
        ->assertOk()
        ->assertViewHas('users', fn ($users) => $users->contains('id', $empleado->id));
});

test('CU06 - se rechaza el alta con un rol que no es de empleado', function () {
    $admin = User::factory()->create(['role_id' => 2]);

    foreach ([1, 2] as $rol) {
        $this->actingAs($admin)->post(route('admin.empleados.store'), datosEmpleado([
            'rol_id' => $rol,
            'empleado_correo' => "rol{$rol}@example.com",
            'empleado_dni' => "3011122{$rol}",
        ]))->assertSessionHasErrors('rol_id');
    }

    $this->assertDatabaseMissing('users', ['email' => 'rol1@example.com']);
    $this->assertDatabaseMissing('users', ['email' => 'rol2@example.com']);
});

test('CU06 - un administrador puede ver el formulario de alta', function () {
    $admin = User::factory()->create(['role_id' => 2]);

    $this->actingAs($admin)
        ->get(route('admin.empleados.create'))
        ->assertOk()
        ->assertViewHas('roles');
});

test('CU06 - un invitado es redirigido al login al dar de alta un empleado', function () {
    $this->post(route('admin.empleados.store'), datosEmpleado())
        ->assertRedirect(route('login'));

    $this->assertDatabaseMissing('users', ['dni' => '30111222']);
});

test('CU06 - un usuario sin rol de administrador no puede dar de alta empleados', function () {
    $encargado = User::factory()->create(['role_id' => 4]);

    $this->actingAs($encargado)
        ->post(route('admin.empleados.store'), datosEmpleado())
        ->assertForbidden();

    $this->assertDatabaseMissing('users', ['dni' => '30111222']);
});

test('CU06 - no se puede dar de alta un empleado sin los datos obligatorios', function () {
    $admin = User::factory()->create(['role_id' => 2]);

    $this->actingAs($admin)
        ->post(route('admin.empleados.store'), [])
        ->assertSessionHasErrors([
            'empleado_nombre',
            'empleado_apellido',
            'empleado_dni',
            'empleado_correo',
            'empleado_contrasena',
            'rol_id',
        ]);
});

test('CU06 - no se puede dar de alta un empleado con un correo inválido', function () {
    $admin = User::factory()->create(['role_id' => 2]);

    $this->actingAs($admin)
        ->post(route('admin.empleados.store'), datosEmpleado(['empleado_correo' => 'no-es-un-correo']))
        ->assertSessionHasErrors('empleado_correo');
});

test('CU06 - no se puede dar de alta un empleado con un DNI ya registrado', function () {
    $admin = User::factory()->create(['role_id' => 2]);
    User::factory()->create(['dni' => '30111222']);

    $this->actingAs($admin)
        ->post(route('admin.empleados.store'), datosEmpleado())
        ->assertSessionHasErrors('empleado_dni');
});

test('CU06 - no se puede dar de alta un empleado con un correo ya registrado', function () {
    $admin = User::factory()->create(['role_id' => 2]);
    User::factory()->create(['email' => 'ana.perez@example.com']);

    $this->actingAs($admin)
        ->post(route('admin.empleados.store'), datosEmpleado())
        ->assertSessionHasErrors('empleado_correo');
});

test('CU06 - no se puede dar de alta un empleado con un rol inexistente', function () {
    $admin = User::factory()->create(['role_id' => 2]);

    $this->actingAs($admin)
        ->post(route('admin.empleados.store'), datosEmpleado(['rol_id' => 9999]))
        ->assertSessionHasErrors('rol_id');
});

test('CU06 - no se puede dar de alta un empleado con una contraseña corta', function () {
    $admin = User::factory()->create(['role_id' => 2]);

    $this->actingAs($admin)
        ->post(route('admin.empleados.store'), datosEmpleado(['empleado_contrasena' => '123']))
        ->assertSessionHasErrors('empleado_contrasena');
});
