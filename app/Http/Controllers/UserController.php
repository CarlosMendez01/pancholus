<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Rol;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Rol::whereNotIn('id', [1, 2])->get(); // 1 = cliente; 2 = administrador.
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
        $roles = Rol::whereNotIn('id', [1, 2])->get();

        return view('admin.empleados.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $request->validate([
            'empleado_nombre' => ['required', 'string', 'max:255'],
            'empleado_apellido' => ['required', 'string', 'max:255'],
            'empleado_dni' => ['required', 'string', 'max:20'],
            'empleado_telefono' => ['required', 'string', 'max:20'],
            'empleado_correo' => ['nullable', 'email', 'max:255'],
            'rol_id' => ['required', 'integer'],
            'empleado_contrasena' => ['required', 'string', 'min:8'],
        ]);

        $empleado = new User;
        $empleado->name = $request->empleado_nombre;
        $empleado->last_name = $request->empleado_apellido;
        $empleado->dni = $request->empleado_dni;
        $empleado->phone = $request->empleado_telefono;
        $empleado->email = $request->empleado_correo;
        $empleado->role_id = $request->rol_id;
        $empleado->password = $request->empleado_contrasena;
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

        $roles = Rol::whereIn('id', [3, 4])->get();

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

        if ($request->filled('empleado_contrasena')) {
            $user->password = $request->input('empleado_contrasena');
        }

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
