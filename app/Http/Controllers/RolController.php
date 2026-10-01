<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use App\Http\Requests\StoreRolRequest;
use App\Http\Requests\UpdateRolRequest;
use App\Models\Rol;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Rol::whereNotIn('id', [1, 2])->get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRolRequest $request)
    {
        $nuevo_rol = new Rol();
        $nuevo_rol->role = $request->input('nuevo_rol');
        $nuevo_rol->save();

        return redirect()->route('admin.roles.index')
        ->with('success', 'Rol agregado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rol $rol)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rol $rol)
    {
        return view('admin.roles.edit', compact('rol'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRolRequest $request, Rol $rol)
    {
        $rol->role = $request->input('rol_actualizado');
        $rol->save();

        return redirect()->route('admin.roles.index')
        ->with('success', 'Rol actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rol $rol)
    {
        $rol->delete();
        return redirect()->route('admin.roles.index')
        ->with('success', 'Rol eliminado exitosamente');
    }
}
