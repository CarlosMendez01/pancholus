<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMealRequest;
use App\Http\Requests\UpdateMealRequest;
use App\Models\Filter;
use App\Models\FoodTruck;
use App\Models\Meal;
use Illuminate\Support\Facades\Storage;

class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($filtro = null)
    {
        $comidas = Meal::query()
            ->when($filtro && (int) $filtro !== 1, fn ($query) => $query->where('filter_id', $filtro))
            ->paginate(12)
            ->withQueryString();

        $all_filters = FilterController::index();
        $foodTrucks = FoodTruck::all();

        $data = ['comidas', 'all_filters', 'foodTrucks'];

        return view('clientes.menu', compact($data));
    }

    public function list()
    {
        $meals = Meal::paginate(10);

        return view('admin.comidas.index', compact('meals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $all_filters = Filter::all();

        return view('admin.comidas.create', compact('all_filters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMealRequest $request)
    {
        $data = $request->validated();

        // `validated()` devuelve el UploadedFile tal cual. Hay que moverlo al
        // disco público y guardar en la BD solo la ruta relativa (meals/archivo.png).
        $data['path'] = $request->file('path')->store('meals', 'public');

        Meal::create($data);

        return redirect()->route('admin.comidas.list')
            ->with('success', 'Nueva comida agregada');
    }

    /**
     * Display the specified resource.
     */
    public function show(Meal $meal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Meal $meal)
    {
        $all_filters = Filter::all();

        return view('admin.comidas.edit', compact('meal', 'all_filters'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMealRequest $request, Meal $meal)
    {
        $data = $request->validated();

        if ($request->hasFile('path')) {
            $data['path'] = $request->file('path')->store('meals', 'public');

            // Borra la foto anterior si estaba dentro del disco público.
            if ($meal->path) {
                Storage::disk('public')->delete($meal->path);
            }
        }

        $meal->update($data);

        return redirect()->route('admin.comidas.list')
            ->with('success', 'Comida actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meal $meal)
    {
        $meal->delete();

        return back()->with('success', 'Comida eliminada exitosamente.');
    }
}
