<?php

use App\Models\User;
use Database\Seeders\RolSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolSeeder::class);
});

test('un invitado ve los formularios de login y registro', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee('BIENVENIDO DE VUELTA');

    $this->get(route('register'))
        ->assertOk()
        ->assertSee('Crear cuenta', false);
});

test('un cliente inicia sesión y es redirigido a su menú', function () {
    $cliente = User::factory()->create([
        'role_id' => 1,
        'email' => 'cliente@example.com',
        'password' => 'secreto123',
    ]);

    $this->post('/login', [
        'email' => 'cliente@example.com',
        'password' => 'secreto123',
    ])->assertRedirect(route('cliente.menu'));

    $this->assertAuthenticatedAs($cliente);
});

test('un empleado inicia sesión y es redirigido a la gestión de pedidos', function () {
    User::factory()->create([
        'role_id' => 3,
        'email' => 'empleado@example.com',
        'password' => 'secreto123',
    ]);

    $this->post('/login', [
        'email' => 'empleado@example.com',
        'password' => 'secreto123',
    ])->assertRedirect(route('empleado.pedidos.index'));

    $this->assertAuthenticated();
});

test('un administrador inicia sesión y es redirigido al área de empleados', function () {
    User::factory()->create([
        'role_id' => 2,
        'email' => 'admin@example.com',
        'password' => 'secreto123',
    ]);

    $this->post('/login', [
        'email' => 'admin@example.com',
        'password' => 'secreto123',
    ])->assertRedirect(route('admin.empleados.index'));

    $this->assertAuthenticated();
});

test('no se inicia sesión con credenciales incorrectas', function () {
    User::factory()->create([
        'role_id' => 1,
        'email' => 'cliente@example.com',
        'password' => 'secreto123',
    ]);

    $this->from(route('login'))
        ->post('/login', [
            'email' => 'cliente@example.com',
            'password' => 'incorrecta',
        ])
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('el login exige correo y contraseña', function () {
    $this->post('/login', [])
        ->assertSessionHasErrors(['email', 'password']);

    $this->assertGuest();
});

test('un usuario autenticado cierra sesión', function () {
    $user = User::factory()->create(['role_id' => 1]);

    $this->actingAs($user)
        ->post(route('logout'))
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

test('un visitante se registra como cliente y la contraseña queda hasheada', function () {
    $this->post('/register', [
        'name' => 'Pancho',
        'dni' => '12.345.678',
        'phone' => '3764123456',
        'email' => 'nuevo@example.com',
        'password' => 'clave1234',
    ])->assertRedirect(route('login'));

    $user = User::firstWhere('email', 'nuevo@example.com');

    expect($user)->not->toBeNull()
        ->and($user->role_id)->toBe(1)
        ->and(Hash::check('clave1234', $user->password))->toBeTrue();

    $this->post('/login', [
        'email' => 'nuevo@example.com',
        'password' => 'clave1234',
    ])->assertRedirect(route('cliente.menu'));

    $this->assertAuthenticatedAs($user);
});

test('el registro exige los datos mínimos', function () {
    $this->post('/register', [])
        ->assertSessionHasErrors(['name', 'dni', 'phone', 'password']);

    $this->assertDatabaseCount('users', 0);
});

test('el registro no permite un DNI repetido', function () {
    User::factory()->create(['dni' => '12.345.678']);

    $this->post('/register', [
        'name' => 'Pancho',
        'dni' => '12.345.678',
        'phone' => '3764123456',
        'password' => 'clave1234',
    ])->assertSessionHasErrors('dni');
});

test('un invitado es redirigido al login en rutas protegidas', function () {
    $this->get(route('cliente.pedidos.index'))->assertRedirect(route('login'));
    $this->get(route('empleado.pedidos.index'))->assertRedirect(route('login'));
    $this->get(route('admin.empleados.index'))->assertRedirect(route('login'));
});

test('el middleware de rol bloquea a usuarios con otro rol', function () {
    $cliente = User::factory()->create(['role_id' => 1]);
    $empleado = User::factory()->create(['role_id' => 3]);

    $this->actingAs($cliente)->get(route('admin.empleados.index'))->assertForbidden();
    $this->actingAs($cliente)->get(route('empleado.pedidos.index'))->assertForbidden();

    $this->actingAs($empleado)->get(route('admin.empleados.index'))->assertForbidden();
    $this->actingAs($empleado)->get(route('cliente.pedidos.index'))->assertForbidden();
});
