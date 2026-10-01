<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('index');
});

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