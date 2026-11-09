<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFoodTruckRequest;
use App\Http\Requests\UpdateFoodTruckRequest;
use App\Models\FoodTruck;
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
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFoodTruckRequest $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFoodTruckRequest $request, FoodTruck $foodTruck)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FoodTruck $foodTruck)
    {
        //
    }
}
