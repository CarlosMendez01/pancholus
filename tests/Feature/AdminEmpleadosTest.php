<?php

use App\Models\User;
use Database\Seeders\RolSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolSeeder::class);

    $this->admin = User::factory()->create(['role_id' => 2]);
});

test('un administrador ve solo empleados activos', function () {
    $empleado = User::factory()->create(['role_id' => 3]);
    $encargado = User::factory()->create(['role_id' => 4]);
    $cliente = User::factory()->create(['role_id' => 1]);
    $otroAdmin = User::factory()->create(['role_id' => 2]);
    $deBaja = User::factory()->create(['role_id' => 3, 'status' => 'baja']);

    $response = $this->actingAs($this->admin)
        ->get(route('admin.empleados.index'))
        ->assertOk();

    $ids = $response->viewData('users')->pluck('id')->all();

    expect($ids)
        ->toContain($empleado->id)
        ->toContain($encargado->id)
        ->not->toContain($cliente->id)
        ->not->toContain($otroAdmin->id)
        ->not->toContain($deBaja->id);

    $roles = $response->viewData('roles')->pluck('id')->all();

    expect($roles)->toContain(3)
        ->toContain(4)
        ->not->toContain(1)
        ->not->toContain(2);
});

test('un administrador ve el formulario para crear un empleado sin los roles del sistema', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.empleados.create'))
        ->assertOk()
        ->assertViewHas('roles', fn ($roles) => $roles->contains('id', 3)
            && $roles->contains('id', 4)
            && ! $roles->contains('id', 1)
            && ! $roles->contains('id', 2));
});

test('un administrador registra un empleado con contraseña hasheada', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.empleados.store'), [
            'empleado_nombre' => 'Juan',
            'empleado_apellido' => 'Pérez',
            'empleado_dni' => '30.111.222',
            'empleado_telefono' => '3764-123456',
            'empleado_correo' => 'Juan.Perez@Example.com',
            'empleado_contrasena' => 'clave1234',
            'rol_id' => 3,
        ])
        ->assertRedirect(route('admin.empleados.index'))
        ->assertSessionHas('success');

    $empleado = User::firstWhere('dni', '30.111.222');

    expect($empleado)->not->toBeNull()
        ->and($empleado->name)->toBe('Juan')
        ->and($empleado->last_name)->toBe('Pérez')
        ->and($empleado->email)->toBe('juan.perez@example.com')
        ->and($empleado->role_id)->toBe(3)
        ->and($empleado->status)->toBe('alta')
        ->and(Hash::check('clave1234', $empleado->password))->toBeTrue();
});

test('el alta de empleado valida los datos obligatorios y el rol permitido', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.empleados.store'), [
            'empleado_nombre' => '',
            'empleado_apellido' => '',
            'empleado_dni' => 'abc',
            'empleado_telefono' => 'no-es-telefono',
            'empleado_correo' => 'no-es-email',
            'empleado_contrasena' => 'corta',
            'rol_id' => 1,
        ])
        ->assertSessionHasErrors([
            'empleado_nombre',
            'empleado_apellido',
            'empleado_dni',
            'empleado_telefono',
            'empleado_correo',
            'empleado_contrasena',
            'rol_id',
        ]);

    $this->assertDatabaseCount('users', 1); // solo el administrador
});

test('no se puede registrar un empleado con DNI o correo repetido', function () {
    User::factory()->create([
        'dni' => '30.111.222',
        'email' => 'repetido@example.com',
        'role_id' => 3,
    ]);

    $this->actingAs($this->admin)
        ->post(route('admin.empleados.store'), [
            'empleado_nombre' => 'Otro',
            'empleado_apellido' => 'Empleado',
            'empleado_dni' => '30.111.222',
            'empleado_telefono' => '3764-123456',
            'empleado_correo' => 'repetido@example.com',
            'empleado_contrasena' => 'clave1234',
            'rol_id' => 3,
        ])
        ->assertSessionHasErrors(['empleado_dni', 'empleado_correo']);
});

test('un administrador ve el formulario de edición de un empleado', function () {
    $empleado = User::factory()->create(['role_id' => 3]);

    $this->actingAs($this->admin)
        ->get(route('admin.empleados.edit', $empleado))
        ->assertOk()
        ->assertViewHas('user', fn ($visto) => $visto->id === $empleado->id)
        ->assertViewHas('roles', fn ($roles) => $roles->pluck('id')->all() === [3, 4]);
});

test('un administrador actualiza los datos de un empleado', function () {
    $empleado = User::factory()->create([
        'role_id' => 3,
        'password' => 'password-vieja',
    ]);

    $this->actingAs($this->admin)
        ->post(route('admin.empleados.update', $empleado), [
            'telefono_actualizado' => '3764-999999',
            'correo_actualizado' => 'Actualizado@Example.com',
            'empleado_contrasena' => 'nueva-clave',
            'rol_actualizado' => 4,
        ])
        ->assertRedirect(route('admin.empleados.index'))
        ->assertSessionHas('success');

    $empleado->refresh();

    expect($empleado->phone)->toBe('3764-999999')
        ->and($empleado->email)->toBe('actualizado@example.com')
        ->and($empleado->role_id)->toBe(4)
        ->and(Hash::check('nueva-clave', $empleado->password))->toBeTrue();
});

test('actualizar un empleado sin contraseña conserva la anterior', function () {
    $empleado = User::factory()->create([
        'role_id' => 3,
        'password' => 'password-vieja',
    ]);
    $hashOriginal = $empleado->password;

    $this->actingAs($this->admin)
        ->post(route('admin.empleados.update', $empleado), [
            'telefono_actualizado' => '3764-111111',
            'correo_actualizado' => $empleado->email,
            'rol_actualizado' => 3,
        ])
        ->assertSessionHasNoErrors();

    expect($empleado->fresh()->password)->toBe($hashOriginal)
        ->and(Hash::check('password-vieja', $empleado->fresh()->password))->toBeTrue();
});

test('la edición de un empleado rechaza un correo ya usado por otro usuario', function () {
    User::factory()->create(['email' => 'ocupado@example.com']);
    $empleado = User::factory()->create(['role_id' => 3]);

    $this->actingAs($this->admin)
        ->post(route('admin.empleados.update', $empleado), [
            'telefono_actualizado' => '3764-111111',
            'correo_actualizado' => 'ocupado@example.com',
            'rol_actualizado' => 3,
        ])
        ->assertSessionHasErrors('correo_actualizado');
});

test('un administrador da de baja a un empleado sin eliminarlo', function () {
    $empleado = User::factory()->create(['role_id' => 3, 'status' => 'alta']);

    $this->actingAs($this->admin)
        ->delete(route('admin.empleados.delete', $empleado))
        ->assertRedirect(route('admin.empleados.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('users', [
        'id' => $empleado->id,
        'status' => 'baja',
    ]);
});

test('un empleado no puede administrar empleados', function () {
    $empleado = User::factory()->create(['role_id' => 3]);

    $this->actingAs($empleado)
        ->post(route('admin.empleados.store'), [])
        ->assertForbidden();

    $this->actingAs($empleado)
        ->delete(route('admin.empleados.delete', $this->admin))
        ->assertForbidden();
});
