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
        $roles = Rol::whereIn('id', [3, 4])->get();
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
        $roles = Rol::whereIn('id', [3, 4])->get();

        return view('admin.empleados.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $empleado = new User;
        $empleado->name = $request->input('empleado_nombre');
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
        $this->empleadoActivo($user);

        $roles = Rol::whereIn('id', [3, 4])->get();

        return view('admin.empleados.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->empleadoActivo($user);

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
        $this->empleadoActivo($user);

        $user->status = 'baja';
        $user->save();

        return redirect()->route('admin.empleados.index')
            ->with('success', 'Empleado dado de baja');
    }

    /**
     * Verifica que el usuario sea un empleado activo o responde 404.
     */
    private function empleadoActivo(User $user): void
    {
        if (! in_array($user->role_id, [3, 4], true) || $user->status !== 'alta') {
            abort(404);
        }
    }
}
