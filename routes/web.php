<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('clientes.index');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/admin/', function () {
    return view('admin/index');
});

Route::get('/admin/empleados/', [UserController::class, 'index'])->name('admin.empleados.index');
Route::get('/admin/empleados/nuevo-empleado', [UserController::class, 'create'])->name('admin.empleados.create');
Route::post('/admin/empleados/nuevo-empleado', [UserController::class, 'store'])->name('admin.empleados.store');
Route::get('/admin/empleados/actualizar-{user}', [UserController::class, 'edit'])->name('admin.empleados.edit');
Route::post('/admin/empleados/actualizar-{user}', [UserController::class, 'update'])->name('admin.empleados.update');
Route::delete('/admin/empleados/eliminar-{user}', [UserController::class, 'delete'])->name('admin.empleados.delete');

// ------------------- CONFIGURACIONES (ADMINISTRADOR) -------------------
Route::get('/admin/configuracion', [RolController::class, 'index'])->name('admin.roles.index');
Route::get('/admin/configuracion/nuevo-rol', [RolController::class, 'create'])->name('admin.roles.create');
Route::post('/admin/configuracion/nuevo-rol', [RolController::class, 'store'])->name('admin.roles.store');
Route::get('/admin/configuracion/actualizar-{rol}', [RolController::class, 'edit'])->name('admin.roles.edit');
Route::post('/admin/configuracion/actualizar-{rol}', [RolController::class, 'update'])->name('admin.roles.update');
Route::delete('/admin/configuracion/eliminar-{rol}', [RolController::class, 'destroy'])->name('admin.roles.delete');
Route::get('/admin/statistics', [StatisticsController::class, 'index'])->name('admin.statistics.index');

// ------------------- CLIENTE -------------------
Route::middleware(['auth', 'role:1'])->group(function () {
    Route::get('/menu', [OrderController::class, 'menu'])->name('cliente.menu');
    Route::post('/carrito/agregar', [OrderController::class, 'addToCart'])->name('cliente.carrito.agregar');
    Route::post('/carrito/quitar', [OrderController::class, 'removeFromCart'])->name('cliente.carrito.quitar');
    Route::post('/pedidos', [OrderController::class, 'store'])->name('cliente.pedidos.store');
    Route::get('/mis-pedidos', [OrderController::class, 'myOrders'])->name('cliente.pedidos.index');
});

// ------------------- EMPLEADO -------------------
Route::middleware(['auth', 'role:3'])->group(function () {
    Route::get('/empleados/inicio', [OrderController::class, 'home'])->name('empleado.inicio');
    Route::get('/empleados', [OrderController::class, 'manage'])->name('empleado.pedidos.index');
    Route::get('/empleados/pedidos/actualizar', [OrderController::class, 'refresh'])->name('empleado.pedidos.actualizar');
    Route::post('/empleados/pedidos/{order}/realizar', [OrderController::class, 'updateStatus'])->name('empleado.pedidos.realizar');
    Route::post('/empleados/pedidos/{order}/cancelar', [OrderController::class, 'updateStatus'])->name('empleado.pedidos.cancelar');
});
