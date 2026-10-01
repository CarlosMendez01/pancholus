<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Models\Rol;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Rol::where('id', '!=', 1)->get();
        $users = User::whereNotIn('role_id', [1, 2])
        ->where('status', 'alta')
        ->get();
        return view('admin.empleados.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Rol::where('id', '!=', 1)->get();
        return view('admin.empleados.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $empleado = new User();
        $empleado->name= $request->input('empleado_nombre');
        $empleado->last_name = $request->input('empleado_apellido');
        $empleado->dni = $request->input('empleado_dni');
        $empleado->phone = $request->input('empleado_telefono');
        $empleado->email = $request->input('empleado_correo');
        $empleado->role_id = $request->input('rol_id');
        $empleado->password = $request->input('empleado_contrasena');

        $empleado->save();
        return redirect()->route('admin.empleados.index')
        ->with('success', 'Nuevo empleado agregado');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Rol::where('id', '!=', 1)->get();
        return view('admin.empleados.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->phone = $request->input('telefono_actualizado');
        $user->email = $request->input('correo_actualizado');
        $user->role_id = $request->input('rol_actualizado');

        $user->save();
        return redirect()->route('admin.empleados.index')
        ->with('success', 'Empleado actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(User $user)
    {
        $user->status = 'baja';
        $user->save();
        
        return redirect()->route('admin.empleados.index')
        ->with('success', 'Empleado dado de baja');
    }
}
