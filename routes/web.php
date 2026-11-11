<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\FoodTruckAssignmentController;
use App\Http\Controllers\FoodTruckController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('clientes.flyer');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ------------------- ADMINISTRADOR -------------------
Route::middleware(['auth', 'role:2'])->group(function () {
    Route::get('/admin/', function () {
        return view('admin/index');
    });

    // ------------------- EMPLEADOS (ADMINISTRADOR) -------------------
    Route::get('/admin/empleados/', [UserController::class, 'index'])->name('admin.empleados.index');
    Route::get('/admin/empleados/nuevo-empleado', [UserController::class, 'create'])->name('admin.empleados.create');
    Route::get('/admin/empleados/actualizar-{user}', [UserController::class, 'edit'])->name('admin.empleados.edit');
    Route::post('/admin/empleados/nuevo-empleado', [UserController::class, 'store'])->name('admin.empleados.store');
    Route::post('/admin/empleados/actualizar-{user}', [UserController::class, 'update'])->name('admin.empleados.update');
    Route::delete('/admin/empleados/eliminar-{user}', [UserController::class, 'delete'])->name('admin.empleados.delete');

    // ------------------- FOODTRUCKS (ADMINISTRADOR) -------------------
    Route::get('/admin/foodtrucks/', [FoodTruckController::class, 'index'])->name('admin.foodtrucks.index');
    Route::get('/admin/foodtrucks/nueva-foodtruck', [FoodTruckController::class, 'create'])->name('admin.foodtrucks.create');
    Route::get('/admin/foodtrucks/actualizar-{foodTruck}', [FoodTruckController::class, 'edit'])->name('admin.foodtrucks.edit');
    Route::post('/admin/foodtrucks/nueva-foodtruck', [FoodTruckController::class, 'store'])->name('admin.foodtrucks.store');
    Route::post('/admin/foodtrucks/actualizar-{foodTruck}', [FoodTruckController::class, 'update'])->name('admin.foodtrucks.update');
    Route::delete('/admin/foodtrucks/eliminar-{foodTruck}', [FoodTruckController::class, 'destroy'])->name('admin.foodtrucks.destroy');

    // ------------------- ASIGNACIONES (ADMINISTRADOR) -------------------
    Route::get('/admin/asignaciones/', [FoodTruckAssignmentController::class, 'index'])->name('admin.asignaciones.index');
    // Route::get('/admin/asignaciones/nueva-asignacion', [FoodTruckAssignmentController::class, 'create'])->name('admin.asignaciones.create');
    // Route::get('/admin/foodtrucks/actualizar-{foodTruck}', [FoodTruckController::class, 'edit'])->name('admin.foodtrucks.edit');
    // Route::post('/admin/foodtrucks/nueva-foodtruck', [FoodTruckController::class, 'store'])->name('admin.foodtrucks.store');
    // Route::post('/admin/foodtrucks/actualizar-{foodTruck}', [FoodTruckController::class, 'update'])->name('admin.foodtrucks.update');
    // Route::delete('/admin/foodtrucks/eliminar-{foodTruck}', [FoodTruckController::class, 'destroy'])->name('admin.foodtrucks.destroy');

    // ------------------- COMIDAS (ADMINISTRADOR) -------------------
    Route::get('/admin/comidas/', [MealController::class, 'list'])->name('admin.comidas.list');
    Route::get('/admin/comidas/nueva-comida', [MealController::class, 'create'])->name('admin.comidas.create');
    Route::get('/admin/comidas/actualizar-{meal}', [MealController::class, 'edit'])->name('admin.comidas.edit');
    Route::post('/admin/comidas/nueva-comida', [MealController::class, 'store'])->name('admin.comidas.store');
    Route::post('/admin/comidas/actualizar-{meal}', [MealController::class, 'update'])->name('admin.comidas.update');
    Route::delete('/admin/comidas/eliminar-{meal}', [MealController::class, 'destroy'])->name('admin.comidas.destroy');

    // ------------------- FILTROS (ADMINISTRADOR) -------------------
    Route::get('/admin/filtros/nuevo-filtro', [FilterController::class, 'create'])->name('admin.filtros.create');
    Route::get('/admin/filtros/actualizar-{filter}', [FilterController::class, 'edit'])->name('admin.filtros.edit');
    Route::post('/admin/filtros/nuevo-filtro', [FilterController::class, 'store'])->name('admin.filtros.store');
    Route::post('/admin/filtros/actualizar-{filter}', [FilterController::class, 'update'])->name('admin.filtros.update');
    Route::delete('/admin/filtros/eliminar-{filter}', [FilterController::class, 'destroy'])->name('admin.filtros.destroy');

    // ------------------- CONFIGURACIONES (ADMINISTRADOR) -------------------
    Route::get('/admin/configuracion', [RolController::class, 'index'])->name('admin.roles.index');
    Route::get('/admin/configuracion/nuevo-rol', [RolController::class, 'create'])->name('admin.roles.create');
    Route::get('/admin/configuracion/actualizar-{rol}', [RolController::class, 'edit'])->name('admin.roles.edit');
    Route::get('/admin/statistics', [StatisticsController::class, 'index'])->name('admin.statistics.index');
    Route::get('/admin/statistics/data', [StatisticsController::class, 'data'])->name('admin.statistics.data');
    Route::post('/admin/configuracion/nuevo-rol', [RolController::class, 'store'])->name('admin.roles.store');
    Route::post('/admin/configuracion/actualizar-{rol}', [RolController::class, 'update'])->name('admin.roles.update');
    Route::delete('/admin/configuracion/eliminar-{rol}', [RolController::class, 'destroy'])->name('admin.roles.delete');
});

// ------------------- CLIENTE (MENU / CARRITO / PEDIDOS) -------------------
Route::get('/menu/{filtro?}', [MealController::class, 'index'])->name('cliente.menu');
Route::get('/carrito', [OrderController::class, 'cart'])->name('cliente.carrito.index');
Route::post('/menu/foodtruck', [FoodTruckController::class, 'select'])->name('cliente.foodtruck.seleccionar');
Route::post('/carrito/agregar', [OrderController::class, 'addToCart'])->name('cliente.carrito.agregar');
Route::post('/carrito/quitar', [OrderController::class, 'removeFromCart'])->name('cliente.carrito.quitar');

Route::middleware(['auth', 'role:1'])->group(function () {
    Route::get('/mis-pedidos', [OrderController::class, 'myOrders'])->name('cliente.pedidos.index');
    Route::post('/pedidos', [OrderController::class, 'store'])->name('cliente.pedidos.store');
});

// ------------------- EMPLEADO -------------------
Route::middleware(['auth', 'role:3'])->group(function () {
    Route::get('/empleados/inicio', [OrderController::class, 'home'])->name('empleado.inicio');
    Route::get('/empleados', [OrderController::class, 'manage'])->name('empleado.pedidos.index');
    Route::get('/empleados/pedidos/actualizar', [OrderController::class, 'refresh'])->name('empleado.pedidos.actualizar');
    Route::post('/empleados/pedidos/{order}/realizar', [OrderController::class, 'updateStatus'])->name('empleado.pedidos.realizar');
    Route::post('/empleados/pedidos/{order}/cancelar', [OrderController::class, 'updateStatus'])->name('empleado.pedidos.cancelar');
    Route::delete('/empleados/pedidos/{order}', [OrderController::class, 'destroy'])->name('empleado.pedidos.destroy');
});
