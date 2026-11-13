<?php

use App\Models\Filter;
use App\Models\User;
use Database\Seeders\FilterSeeder;
use Database\Seeders\RolSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed([
        RolSeeder::class,
        FilterSeeder::class,
    ]);

    $this->admin = User::factory()->create(['role_id' => 2]);
});


test('un administrador ve el formulario para crear un filtro', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.filtros.create'))
        ->assertOk()
        ->assertSee('Agregar filtro');
});

test('un administrador crea un filtro y vuelve al listado de comidas', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.filtros.store'), ['name' => 'veggie especial'])
        ->assertRedirect(route('admin.comidas.list'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('filters', ['name' => 'veggie especial']);
});

test('crear un filtro respeta una URL de retorno interna válida', function () {
    $returnTo = url('/admin/comidas/nueva-comida');

    $this->actingAs($this->admin)
        ->post(route('admin.filtros.store'), [
            'name' => 'retorno interno',
            'return_to' => $returnTo,
        ])
        ->assertRedirect($returnTo);

    $this->assertDatabaseHas('filters', ['name' => 'retorno interno']);
});

test('crear un filtro descarta una URL de retorno externa', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.filtros.store'), [
            'name' => 'retorno externo',
            'return_to' => 'https://sitio-malicioso.example/phishing',
        ])
        ->assertRedirect(route('admin.comidas.list'));

    $this->assertDatabaseHas('filters', ['name' => 'retorno externo']);
});

test('el alta de filtro exige un nombre único y no vacío', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.filtros.store'), ['name' => ''])
        ->assertSessionHasErrors('name');

    $this->actingAs($this->admin)
        ->post(route('admin.filtros.store'), ['name' => 'combos'])
        ->assertSessionHasErrors('name');
});

test('un administrador ve el formulario de edición de un filtro', function () {
    $filter = Filter::firstWhere('name', 'bebidas');

    $this->actingAs($this->admin)
        ->get(route('admin.filtros.edit', $filter))
        ->assertOk()
        ->assertSee('bebidas');
});

test('un administrador actualiza un filtro', function () {
    $filter = Filter::create(['name' => 'temporal']);

    $this->actingAs($this->admin)
        ->post(route('admin.filtros.update', $filter), ['name' => 'renombrado'])
        ->assertRedirect(route('admin.comidas.list'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('filters', [
        'id' => $filter->id,
        'name' => 'renombrado',
    ]);
});

test('mantener el nombre del propio filtro no da error de duplicado', function () {
    $filter = Filter::create(['name' => 'estable']);

    $this->actingAs($this->admin)
        ->post(route('admin.filtros.update', $filter), ['name' => 'estable'])
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('filters', ['id' => $filter->id, 'name' => 'estable']);
});

test('actualizar un filtro rechaza nombres vacíos o repetidos', function () {
    $filter = Filter::create(['name' => 'propio']);

    $this->actingAs($this->admin)
        ->post(route('admin.filtros.update', $filter), ['name' => ''])
        ->assertSessionHasErrors('name');

    $this->actingAs($this->admin)
        ->post(route('admin.filtros.update', $filter), ['name' => 'combos'])
        ->assertSessionHasErrors('name');
});

test('un administrador elimina lógicamente un filtro', function () {
    $filter = Filter::create(['name' => 'descartable']);

    $this->actingAs($this->admin)
        ->from(route('admin.comidas.list'))
        ->delete(route('admin.filtros.destroy', $filter))
        ->assertRedirect(route('admin.comidas.list'))
        ->assertSessionHas('success');

    $this->assertSoftDeleted('filters', ['id' => $filter->id]);
});

test('un empleado no puede gestionar filtros', function () {
    $empleado = User::factory()->create(['role_id' => 3]);

    $this->actingAs($empleado)
        ->get(route('admin.filtros.create'))
        ->assertForbidden();

    $this->actingAs($empleado)
        ->post(route('admin.filtros.store'), ['name' => 'no autorizado'])
        ->assertForbidden();
});
