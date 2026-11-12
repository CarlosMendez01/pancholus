<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePDV_assignmentRequest;
use App\Http\Requests\UpdatePDV_assignmentRequest;
use App\Models\FoodTruck;
use App\Models\PDV;
use App\Models\PDV_assignment;

class PDVAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pdvAssignments = PDV_assignment::with(['pdv', 'foodTruck'])->get();

        return view('admin.puntos_de_venta.index', compact('pdvAssignments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $foodTrucks = FoodTruck::all();

        return view('admin.puntos_de_venta.create', compact('foodTrucks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePDV_assignmentRequest $request)
    {
        $validated = $request->validated();

        $pdv = PDV::create([
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ]);

        PDV_assignment::create([
            'PDV_id' => $pdv->id,
            'food_truck_id' => $validated['food_truck_id'] ?? null,
        ]);

        return redirect()
            ->route('admin.pdvs.index')
            ->with('success', 'Punto de venta creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PDV_assignment $pDV_assignment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PDV_assignment $pDV_assignment)
    {
        $pDV_assignment->load('pdv');

        $foodTrucks = FoodTruck::orderBy('license_plate')->get();

        return view('admin.puntos_de_venta.edit', [
            'assignment' => $pDV_assignment,
            'foodTrucks' => $foodTrucks,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePDV_assignmentRequest $request, PDV_assignment $pDV_assignment)
    {
        $validated = $request->validated();

        $pDV_assignment->pdv->update([
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ]);

        $pDV_assignment->update([
            'food_truck_id' => $validated['food_truck_id'],
        ]);

        return redirect()
            ->route('admin.pdvs.index')
            ->with('success', 'Punto de venta actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PDV_assignment $pDV_assignment)
    {
        $pDV_assignment->delete();

        return back();
    }
}
