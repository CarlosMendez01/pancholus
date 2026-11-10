<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFoodTruckRequest;
use App\Http\Requests\UpdateFoodTruckRequest;
use App\Models\FoodTruck;
use App\Models\FoodTruckAssignment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FoodTruckController extends Controller
{
    /**
     * Guarda en la sesión el food truck seleccionado por el cliente.
     */
    public function select(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'food_truck_id' => ['required', 'integer'],
        ]);

        $request->session()->put('foodtruck', $validated['food_truck_id']);

        return redirect()->back();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $foodTrucks = FoodTruck::all();

        return view('admin.foodtrucks.index', compact('foodTrucks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.foodtrucks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(StoreFoodTruckRequest $request)
    {
        FoodTruck::create($request->validated());

        return redirect()->route('admin.foodtrucks.index')
            ->with('success', 'Nueva foodtruck agregada');
    }

    /**
     * Display the specified resource.
     */
    public function show(FoodTruck $foodTruck)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FoodTruck $foodTruck)
    {
        return view('admin.foodtrucks.edit', compact('foodTruck'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFoodTruckRequest $request, FoodTruck $foodTruck)
    {
        $foodTruck->update($request->validated());

        return redirect()->route('admin.foodtrucks.index')
            ->with('success', 'Foodtruck actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FoodTruck $foodTruck)
    {
        $foodTruck_assignment = FoodTruckAssignment::where('food_truck_id', $foodTruck->id);

        $foodTruck->delete();
        $foodTruck_assignment->delete();

        return back()->with('success', "Foodtruck {$foodTruck->license_plate} eliminada exitosamente.");
    }
}
