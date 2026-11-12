<?php

use App\Models\Filter;
use App\Models\FoodTruck;
use App\Models\Meal;
use App\Models\Order;
use App\Models\OrderDetail;
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
});

/*
|------------------------------------------------------------------------------
| Cliente - Menú, selección de foodtruck, carrito y pedidos
|------------------------------------------------------------------------------
| El menú es público, el carrito vive en la sesión y confirmar un pedido
| requiere estar autenticado y haber elegido un foodtruck.
*/

test('el menú muestra todas las comidas disponibles', function () {
    $meal = Meal::factory()->create(['name' => 'Pancho visible']);

    $response = $this->get(route('cliente.menu'))
        ->assertOk()
        ->assertSee('Pancho visible');

    expect($response->viewData('comidas')->pluck('id')->all())->toContain($meal->id);
    expect($response->viewData('all_filters')->contains('id', 1))->toBeTrue();
});

test('el menú filtra las comidas por categoría', function () {
    $filtro = Filter::create(['name' => 'categoria de prueba']);
    $otroFiltro = Filter::create(['name' => 'otra categoria']);

    $propia = Meal::factory()->create([
        'name' => 'Comida filtrada',
        'filter_id' => $filtro->id,
    ]);
    $ajena = Meal::factory()->create([
        'name' => 'Comida ajena',
        'filter_id' => $otroFiltro->id,
    ]);

    $response = $this->get(route('cliente.menu', $filtro->id))
        ->assertOk();

    $ids = $response->viewData('comidas')->pluck('id')->all();

    expect($ids)->toContain($propia->id)
        ->not->toContain($ajena->id);
});

test('el filtro 1 representa todos los productos y no filtra', function () {
    $filtroA = Filter::create(['name' => 'filtro a']);
    $filtroB = Filter::create(['name' => 'filtro b']);

    $mealA = Meal::factory()->create(['filter_id' => $filtroA->id]);
    $mealB = Meal::factory()->create(['filter_id' => $filtroB->id]);

    $ids = $this->get(route('cliente.menu', 1))
        ->assertOk()
        ->viewData('comidas')
        ->pluck('id')
        ->all();

    expect($ids)->toContain($mealA->id)->toContain($mealB->id);
});

test('el cliente selecciona el foodtruck y queda en la sesión', function () {
    $foodTruck = FoodTruck::factory()->create();

    $this->from(route('cliente.menu'))
        ->post(route('cliente.foodtruck.seleccionar'), [
            'food_truck_id' => $foodTruck->id,
        ])
        ->assertRedirect(route('cliente.menu'));

    expect(session('foodtruck'))->toBe($foodTruck->id);
});

test('la selección de foodtruck exige un identificador', function () {
    $this->post(route('cliente.foodtruck.seleccionar'), [])
        ->assertSessionHasErrors('food_truck_id');
});

test('agregar una comida guarda nombre, precio y cantidad en el carrito', function () {
    $meal = Meal::factory()->create([
        'name' => 'Pancho carrito',
        'price' => 1200,
    ]);

    $this->from(route('cliente.menu'))
        ->post(route('cliente.carrito.agregar'), [
            'meal_id' => $meal->id,
            'quantity' => 3,
        ])
        ->assertRedirect(route('cliente.menu'))
        ->assertSessionHas('success');

    $cart = session('cart');

    expect($cart)->toHaveKey($meal->id)
        ->and($cart[$meal->id]['meal_name'])->toBe('Pancho carrito')
        ->and((float) $cart[$meal->id]['meal_price'])->toBe(1200.0)
        ->and($cart[$meal->id]['meal_quantity'])->toBe(3);
});

test('la cantidad agregada al carrito debe ser válida', function () {
    $meal = Meal::factory()->create();

    $this->post(route('cliente.carrito.agregar'), [
        'meal_id' => $meal->id,
        'quantity' => 0,
    ])->assertSessionHasErrors('quantity');

    $this->post(route('cliente.carrito.agregar'), [
        'meal_id' => $meal->id,
        'quantity' => 100,
    ])->assertSessionHasErrors('quantity');
});

test('quitar una comida la elimina del carrito', function () {
    $meal = Meal::factory()->create();

    $this->withSession([
        'cart' => [
            $meal->id => [
                'meal_id' => $meal->id,
                'meal_name' => $meal->name,
                'meal_price' => $meal->price,
                'meal_quantity' => 2,
            ],
        ],
    ])
        ->from(route('cliente.carrito.index'))
        ->post(route('cliente.carrito.quitar'), ['meal_id' => $meal->id])
        ->assertRedirect(route('cliente.carrito.index'));

    expect(session('cart'))->toBe([]);
});

test('el carrito muestra los productos de la sesión y el total', function () {
    $meal = Meal::factory()->create([
        'name' => 'Pancho en carrito',
        'price' => 1000,
    ]);

    $this->withSession([
        'cart' => [
            $meal->id => [
                'meal_id' => $meal->id,
                'meal_name' => $meal->name,
                'meal_price' => $meal->price,
                'meal_quantity' => 2,
            ],
        ],
    ])
        ->get(route('cliente.carrito.index'))
        ->assertOk()
        ->assertSee('Pancho en carrito')
        ->assertSee('$2.000');
});

test('el carrito vacío muestra su estado vacío', function () {
    $this->get(route('cliente.carrito.index'))
        ->assertOk()
        ->assertSee('Tu carrito está vacío.');
});

test('confirmar un pedido sin foodtruck seleccionado no crea la orden', function () {
    $cliente = User::factory()->create(['role_id' => 1]);
    $meal = Meal::factory()->create();

    $this->actingAs($cliente)
        ->withSession([
            'cart' => [
                $meal->id => [
                    'meal_id' => $meal->id,
                    'meal_quantity' => 1,
                ],
            ],
        ])
        ->post(route('cliente.pedidos.store'))
        ->assertRedirect(route('cliente.menu'))
        ->assertSessionHasErrors('foodtruck');

    $this->assertDatabaseCount('orders', 0);
});

test('confirmar un pedido lo guarda, crea sus detalles y vacía el carrito', function () {
    $cliente = User::factory()->create(['role_id' => 1]);
    $meal = Meal::factory()->create();
    $foodTruck = FoodTruck::factory()->create();

    $this->actingAs($cliente)
        ->withSession([
            'foodtruck' => $foodTruck->id,
            'cart' => [
                $meal->id => [
                    'meal_id' => $meal->id,
                    'meal_quantity' => 2,
                ],
            ],
        ])
        ->post(route('cliente.pedidos.store'))
        ->assertRedirect(route('cliente.menu'))
        ->assertSessionHas('success');

    $order = Order::firstWhere('user_id', $cliente->id);

    expect($order)->not->toBeNull()
        ->and($order->foodtruck_id)->toBe($foodTruck->id)
        ->and($order->status)->toBe(Order::STATUS_PENDIENTE)
        ->and(session('cart'))->toBe([]);

    $this->assertDatabaseHas('order_details', [
        'order_id' => $order->id,
        'meal_id' => $meal->id,
        'quantity' => 2,
    ]);
});

test('un cliente ve únicamente sus propios pedidos', function () {
    $cliente = User::factory()->create(['role_id' => 1]);
    $otroCliente = User::factory()->create(['role_id' => 1]);

    $propio = Order::factory()->create(['user_id' => $cliente->id]);
    $ajeno = Order::factory()->create(['user_id' => $otroCliente->id]);

    OrderDetail::factory()->create([
        'order_id' => $propio->id,
        'quantity' => 1,
    ]);

    $response = $this->actingAs($cliente)
        ->get(route('cliente.pedidos.index'))
        ->assertOk()
        ->assertSee("Pedido #{$propio->id}");

    $ids = $response->viewData('orders')->pluck('id')->all();

    expect($ids)->toContain($propio->id)
        ->not->toContain($ajeno->id)
        ->and($response->getContent())->not->toContain("Pedido #{$ajeno->id}");
});

test('un empleado no puede confirmar pedidos como cliente', function () {
    $empleado = User::factory()->create(['role_id' => 3]);

    $this->actingAs($empleado)
        ->post(route('cliente.pedidos.store'))
        ->assertForbidden();
});
