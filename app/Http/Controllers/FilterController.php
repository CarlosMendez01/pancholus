<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFilterRequest;
use App\Http\Requests\UpdateFilterRequest;
use App\Models\Filter;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public static function index() // acceder al método sin instanciar la clase.
    {
        return Filter::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.filtros.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFilterRequest $request)
    {
        Filter::create(['name' => $request->validated('name')]);

        return $this->redirectToMealPage($request, 'Filtro creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Filter $filter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Filter $filter)
    {
        return view('admin.filtros.edit', compact('filter'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFilterRequest $request, Filter $filter)
    {
        $filter->update(['name' => $request->validated('name')]);

        return $this->redirectToMealPage($request, 'Filtro actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Filter $filter)
    {
        $filter->delete();

        return back()->with('success', 'Filtro eliminado exitosamente.');
    }

    /**
     * Vuelve a la vista de comida desde la que se abrió el formulario del filtro.
     * Solo acepta URLs internas del área de comidas para evitar redirecciones externas.
     */
    private function redirectToMealPage(Request $request, string $message)
    {
        $returnTo = (string) $request->input('return_to', '');

        $isMealPage = str_starts_with($returnTo, url('/admin/comidas/'))
            || str_starts_with($returnTo, '/admin/comidas/');

        if ($isMealPage) {
            return redirect()->to($returnTo)->with('success', $message);
        }

        return redirect()->route('admin.comidas.list')->with('success', $message);
    }
}
