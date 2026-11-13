<?php

use App\Models\Filter;
use App\Models\Meal;
use App\Models\User;
use Database\Seeders\FilterSeeder;
use Database\Seeders\RolSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed([
        RolSeeder::class,
        FilterSeeder::class,
    ]);

    $this->admin = User::factory()->create(['role_id' => 2]);
});

test('un administrador ve el listado de comidas', function () {
    $meal = Meal::factory()->create(['name' => 'Pancho especial']);

    $this->actingAs($this->admin)
        ->get(route('admin.comidas.list'))
        ->assertOk()
        ->assertSee('Pancho especial')
        ->assertViewHas('meals', fn ($meals) => $meals->contains('id', $meal->id));
});

test('un administrador ve el formulario para crear una comida con sus filtros', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.comidas.create'))
        ->assertOk()
        ->assertSee('Agregar comida')
        ->assertViewHas('all_filters', fn ($filters) => $filters->contains('name', 'combos'));
});

test('un administrador crea una comida y guarda la foto en el disco público', function () {
    Storage::fake('public');

    $filtro = Filter::firstWhere('name', 'combos');
    $imagen = UploadedFile::fake()->image('pancho.jpg', 600, 400);

    $this->actingAs($this->admin)
        ->post(route('admin.comidas.store'), [
            'name' => 'Pancho de prueba',
            'price' => 1500,
            'details' => 'Pancho con queso y panceta',
            'path' => $imagen,
            'filter_id' => $filtro->id,
        ])
        ->assertRedirect(route('admin.comidas.list'))
        ->assertSessionHas('success');

    $meal = Meal::firstWhere('name', 'Pancho de prueba');

    expect($meal)->not->toBeNull()
        ->and($meal->price)->toEqual(1500)
        ->and($meal->filter_id)->toBe($filtro->id)
        ->and($meal->path)->toStartWith('meals/');

    Storage::disk('public')->assertExists($meal->path);
});

test('el alta de comida exige foto, nombre único, precio no negativo y filtro existente', function () {
    Meal::factory()->create(['name' => 'Repetida']);

    $this->actingAs($this->admin)
        ->post(route('admin.comidas.store'), [
            'name' => 'Repetida',
            'price' => -10,
            'details' => '',
            'filter_id' => 999,
        ])
        ->assertSessionHasErrors(['name', 'price', 'details', 'path', 'filter_id']);
});

test('un administrador ve el formulario de edición de una comida', function () {
    $meal = Meal::factory()->create(['name' => 'Editable']);

    $this->actingAs($this->admin)
        ->get(route('admin.comidas.edit', $meal))
        ->assertOk()
        ->assertSee('Editable')
        ->assertViewHas('meal', fn ($vista) => $vista->id === $meal->id);
});

test('actualizar una comida sin foto conserva la imagen actual', function () {
    Storage::fake('public');

    $meal = Meal::factory()->create([
        'name' => 'Con foto',
        'path' => 'meals/actual.jpg',
    ]);

    $this->actingAs($this->admin)
        ->post(route('admin.comidas.update', $meal), [
            'name' => 'Con foto editada',
            'price' => 2000,
            'details' => 'Nueva descripción',
            'filter_id' => $meal->filter_id,
        ])
        ->assertRedirect(route('admin.comidas.list'))
        ->assertSessionHas('success');

    expect($meal->fresh())
        ->name->toBe('Con foto editada')
        ->path->toBe('meals/actual.jpg');
});

test('actualizar la foto reemplaza el archivo anterior', function () {
    Storage::fake('public');
    Storage::disk('public')->put('meals/vieja.jpg', 'contenido');

    $meal = Meal::factory()->create([
        'name' => 'Cambia foto',
        'path' => 'meals/vieja.jpg',
    ]);

    $this->actingAs($this->admin)
        ->post(route('admin.comidas.update', $meal), [
            'name' => 'Cambia foto',
            'price' => $meal->price,
            'details' => $meal->details,
            'filter_id' => $meal->filter_id,
            'path' => UploadedFile::fake()->image('nueva.png'),
        ])
        ->assertRedirect(route('admin.comidas.list'));

    $meal->refresh();

    expect($meal->path)->not->toBe('meals/vieja.jpg');

    Storage::disk('public')->assertMissing('meals/vieja.jpg');
    Storage::disk('public')->assertExists($meal->path);
});

test('la edición de comida no permite repetir el nombre de otra comida activa', function () {
    Meal::factory()->create(['name' => 'Ocupado']);
    $meal = Meal::factory()->create(['name' => 'Libre']);

    $this->actingAs($this->admin)
        ->post(route('admin.comidas.update', $meal), [
            'name' => 'Ocupado',
            'price' => $meal->price,
            'details' => $meal->details,
            'filter_id' => $meal->filter_id,
        ])
        ->assertSessionHasErrors('name');
});

test('un administrador elimina lógicamente una comida', function () {
    $meal = Meal::factory()->create();

    $this->actingAs($this->admin)
        ->from(route('admin.comidas.list'))
        ->delete(route('admin.comidas.destroy', $meal))
        ->assertRedirect(route('admin.comidas.list'))
        ->assertSessionHas('success');

    $this->assertSoftDeleted('meals', ['id' => $meal->id]);
});

test('un empleado no puede gestionar comidas', function () {
    $empleado = User::factory()->create(['role_id' => 3]);

    $this->actingAs($empleado)
        ->get(route('admin.comidas.list'))
        ->assertForbidden();

    $this->actingAs($empleado)
        ->delete(route('admin.comidas.destroy', Meal::factory()->create()))
        ->assertForbidden();
});
