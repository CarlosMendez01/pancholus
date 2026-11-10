@extends('admin.index')

@section('title', 'Asignaciones')

@section('content')

<div class="max-w-4xl mx-auto my-8 p-6 bg-white rounded-xl shadow-md border border-gray-100">

    <!-- Encabezado de sección -->
    <div class="flex items-center justify-between pb-4 mb-6 border-b border-gray-200">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Asignaciones</h1>
            <p class="text-sm text-gray-500">Foodtrucks registradas en el sistema</p>
        </div>
    </div>

    <!-- Acordeón de foodtrucks -->
    <div class="space-y-3">
        @forelse($foodTrucks as $foodTruck)
            <details class="group bg-gray-50 border border-gray-200 rounded-lg overflow-hidden transition-all duration-200 open:bg-white open:ring-2 open:ring-indigo-500 open:border-indigo-500">
                <!-- Encabezado del acordeón: patente de la foodtruck -->
                <summary class="flex items-center justify-between px-4 py-3 cursor-pointer select-none group-open:border-b group-open:border-gray-200 group-open:bg-gray-50">
                    <span class="flex items-center gap-3">
                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-mono font-medium text-gray-700 bg-white border border-gray-200 rounded-md tracking-wider">
                            {{ $foodTruck->license_plate }}
                        </span>
                    </span>

                    <svg class="w-4 h-4 text-gray-400 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </summary>

                <div class="p-4">
                    {{--
                        ============================================================
                        OPCIONES DEL ACORDEÓN
                        Agregar acá las opciones/contenido de la foodtruck
                        (por ejemplo: empleados asignados, PDVs, stock, etc.)
                        ============================================================
                    --}}
                    <div class="p-4 text-center text-sm text-gray-500 bg-gray-50 border border-dashed border-gray-300 rounded-lg">
                        Sin opciones configuradas todavía.
                    </div>
                </div>
            </details>
        @empty
            <div class="p-8 text-center bg-gray-50 rounded-lg border border-dashed border-gray-300">
                <p class="text-gray-500 text-sm">No hay foodtrucks registradas actualmente.</p>
            </div>
        @endforelse
    </div>
</div>

@endsection
