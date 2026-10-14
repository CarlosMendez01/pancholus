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
| CU07 - Modificar empleado
|------------------------------------------------------------------------------
| Datos reales: el administrador actualiza teléfono, correo y rol del empleado.
| Datos falsos: un rol que no es de empleado (administrador/inexistente) o un
| usuario que no es un empleado activo, que se rechazan.
*/

test('CU07 - un administrador actualiza los datos de contacto de un empleado', function () {
    $admin = User::factory()->create(['role_id' => 2]);
    $empleado = User::factory()->create(['role_id' => 3]);

    $this->actingAs($admin)
        ->post(route('admin.empleados.update', $empleado), [
            'telefono_actualizado' => '1199887766',
            'correo_actualizado' => 'nuevo.correo@example.com',
            'rol_actualizado' => 3,
        ])
        ->assertRedirect(route('admin.empleados.index'));

    $this->assertDatabaseHas('users', [
        'id' => $empleado->id,
        'phone' => '1199887766',
        'email' => 'nuevo.correo@example.com',
        'role_id' => 3,
    ]);
});

test('CU07 - el empleado actualizado sigue figurando en el listado', function () {
    $admin = User::factory()->create(['role_id' => 2]);
    $empleado = User::factory()->create(['role_id' => 3]);

    $this->actingAs($admin)->post(route('admin.empleados.update', $empleado), [
        'telefono_actualizado' => '1199887766',
        'correo_actualizado' => 'nuevo.correo@example.com',
        'rol_actualizado' => 4,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.empleados.index'))
        ->assertOk()
        ->assertViewHas('users', fn ($users) => $users->contains('id', $empleado->id));
});

test('CU07 - no se puede cambiar el rol a uno que no es de empleado', function () {
    $admin = User::factory()->create(['role_id' => 2]);
    $empleado = User::factory()->create(['role_id' => 3]);

    $this->actingAs($admin)->post(route('admin.empleados.update', $empleado), [
        'telefono_actualizado' => $empleado->phone,
        'correo_actualizado' => $empleado->email,
        'rol_actualizado' => 2,
    ])->assertSessionHasErrors('rol_actualizado');

    expect($empleado->fresh()->role_id)->toBe(3);
});

test('CU07 - un administrador puede ver el formulario de edición', function () {
    $admin = User::factory()->create(['role_id' => 2]);
    $empleado = User::factory()->create(['role_id' => 3]);

    $this->actingAs($admin)
        ->get(route('admin.empleados.edit', $empleado))
        ->assertOk()
        ->assertViewHas('user', fn ($user) => $user->id === $empleado->id);
});

test('CU07 - mantener el correo del propio empleado no da error de duplicado', function () {
    $admin = User::factory()->create(['role_id' => 2]);
    $empleado = User::factory()->create(['role_id' => 3]);

    $this->actingAs($admin)->post(route('admin.empleados.update', $empleado), [
        'telefono_actualizado' => $empleado->phone,
        'correo_actualizado' => $empleado->email,
        'rol_actualizado' => 3,
    ])->assertSessionHasNoErrors();
});

test('CU07 - el administrador puede actualizar la contraseña del empleado', function () {
    $admin = User::factory()->create(['role_id' => 2]);
    $empleado = User::factory()->create(['role_id' => 3]);

    $this->actingAs($admin)->post(route('admin.empleados.update', $empleado), [
        'telefono_actualizado' => $empleado->phone,
        'correo_actualizado' => $empleado->email,
        'rol_actualizado' => 3,
        'empleado_contrasena' => 'nuevaClave123',
    ])->assertRedirect(route('admin.empleados.index'));

    expect(Hash::check('nuevaClave123', $empleado->fresh()->password))->toBeTrue();
});

test('CU07 - si no se envía contraseña, la actual se conserva', function () {
    $admin = User::factory()->create(['role_id' => 2]);
    $empleado = User::factory()->create(['role_id' => 3]);
    $hashOriginal = $empleado->password;

    $this->actingAs($admin)->post(route('admin.empleados.update', $empleado), [
        'telefono_actualizado' => $empleado->phone,
        'correo_actualizado' => $empleado->email,
        'rol_actualizado' => 3,
    ]);

    expect($empleado->fresh()->password)->toBe($hashOriginal);
});

test('CU07 - un invitado es redirigido al login al modificar un empleado', function () {
    $empleado = User::factory()->create(['role_id' => 3, 'phone' => '1100000000']);

    $this->post(route('admin.empleados.update', $empleado), [
        'telefono_actualizado' => '1199887766',
        'correo_actualizado' => $empleado->email,
        'rol_actualizado' => 3,
    ])->assertRedirect(route('login'));

    expect($empleado->fresh()->phone)->toBe('1100000000');
});

test('CU07 - un usuario sin rol de administrador no puede modificar empleados', function () {
    $empleado = User::factory()->create(['role_id' => 3]);
    $otro = User::factory()->create(['role_id' => 3, 'phone' => '1100000000']);

    $this->actingAs($empleado)->post(route('admin.empleados.update', $otro), [
        'telefono_actualizado' => '1199887766',
        'correo_actualizado' => $otro->email,
        'rol_actualizado' => 3,
    ])->assertForbidden();

    expect($otro->fresh()->phone)->toBe('1100000000');
});

test('CU07 - no se puede modificar un empleado con un correo inválido', function () {
    $admin = User::factory()->create(['role_id' => 2]);
    $empleado = User::factory()->create(['role_id' => 3]);

    $this->actingAs($admin)->post(route('admin.empleados.update', $empleado), [
        'telefono_actualizado' => $empleado->phone,
        'correo_actualizado' => 'no-es-un-correo',
        'rol_actualizado' => 3,
    ])->assertSessionHasErrors('correo_actualizado');
});

test('CU07 - no se puede usar un correo que ya pertenece a otro usuario', function () {
    $admin = User::factory()->create(['role_id' => 2]);
    $empleado = User::factory()->create(['role_id' => 3]);
    User::factory()->create(['email' => 'ocupado@example.com']);

    $this->actingAs($admin)->post(route('admin.empleados.update', $empleado), [
        'telefono_actualizado' => $empleado->phone,
        'correo_actualizado' => 'ocupado@example.com',
        'rol_actualizado' => 3,
    ])->assertSessionHasErrors('correo_actualizado');
});

test('CU07 - no se puede modificar un empleado con un rol inexistente', function () {
    $admin = User::factory()->create(['role_id' => 2]);
    $empleado = User::factory()->create(['role_id' => 3]);

    $this->actingAs($admin)->post(route('admin.empleados.update', $empleado), [
        'telefono_actualizado' => $empleado->phone,
        'correo_actualizado' => $empleado->email,
        'rol_actualizado' => 9999,
    ])->assertSessionHasErrors('rol_actualizado');
});

test('CU07 - no se puede modificar un empleado sin indicar el rol', function () {
    $admin = User::factory()->create(['role_id' => 2]);
    $empleado = User::factory()->create(['role_id' => 3]);

    $this->actingAs($admin)->post(route('admin.empleados.update', $empleado), [
        'telefono_actualizado' => $empleado->phone,
        'correo_actualizado' => $empleado->email,
    ])->assertSessionHasErrors('rol_actualizado');
});

test('CU07 - modificar un empleado inexistente devuelve 404', function () {
    $admin = User::factory()->create(['role_id' => 2]);

    $this->actingAs($admin)->post(route('admin.empleados.update', 999999), [
        'telefono_actualizado' => '1199887766',
        'correo_actualizado' => 'nuevo@example.com',
        'rol_actualizado' => 3,
    ])->assertNotFound();
});

test('CU07 - modificar un usuario que no es un empleado activo devuelve 404', function () {
    $admin = User::factory()->create(['role_id' => 2]);
    $cliente = User::factory()->create(['role_id' => 1]);
    $baja = User::factory()->create(['role_id' => 3, 'status' => 'baja']);

    foreach ([$cliente, $baja] as $objetivo) {
        $this->actingAs($admin)->post(route('admin.empleados.update', $objetivo), [
            'telefono_actualizado' => '1199887766',
            'correo_actualizado' => $objetivo->email,
            'rol_actualizado' => 3,
        ])->assertNotFound();
    }
});
