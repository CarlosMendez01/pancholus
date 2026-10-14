<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMealRequest;
use App\Http\Requests\UpdateMealRequest;
use App\Models\Meal;
use Illuminate\Support\Facades\Storage;

class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($filtro = null)
    {
        switch ($filtro) {
            case 2:
                $comidas = Meal::where('filter_id', 2)->get();
                break;
            default:
                $comidas = Meal::all();
                break;
        }

        $all_filters = FilterController::index();

        $data = ['comidas', 'all_filters'];

        return view('clientes.menu', compact($data));
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
    public function store(StoreMealRequest $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMealRequest $request, Meal $meal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meal $meal)
    {
        //
    }
}
