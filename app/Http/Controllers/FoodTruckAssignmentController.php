<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFoodTruckAssignmentRequest;
use App\Http\Requests\UpdateFoodTruckAssignmentRequest;
use App\Models\FoodTruck;
use App\Models\FoodTruckAssignment;

class FoodTruckAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $foodTrucks = FoodTruck::with(['assignments.user' => fn ($query) => $query
            ->orderBy('last_name')
            ->orderBy('name'),
        ])->get();

        $maxEmpleados = FoodTruck::MAX_EMPLEADOS;

        return view('admin.asignaciones.index', compact('foodTrucks', 'maxEmpleados'));
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
    public function store(StoreFoodTruckAssignmentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(FoodTruckAssignment $foodTruckAssignment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FoodTruckAssignment $foodTruckAssignment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFoodTruckAssignmentRequest $request, FoodTruckAssignment $foodTruckAssignment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FoodTruckAssignment $foodTruckAssignment)
    {
        $foodTruckAssignment->delete();

        return back();
    }
}
