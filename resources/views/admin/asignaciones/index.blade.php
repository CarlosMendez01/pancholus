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

        <button type="button"
            title="Agregar asignación"
            aria-label="Agregar asignación"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 active:bg-indigo-800 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Agregar asignación
        </button>
    </div>

    <!-- Acordeón de foodtrucks -->
    <div class="space-y-3">
        @forelse($foodTrucks as $foodTruck)
            @php($assignments = $foodTruck->assignments->filter(fn ($assignment) => $assignment->user))
            @php($empleados = $assignments->pluck('user'))
            <details class="group bg-gray-50 border border-gray-200 rounded-lg overflow-hidden transition-all duration-200 open:bg-white open:ring-2 open:ring-indigo-500 open:border-indigo-500">
                <!-- Encabezado del acordeón: patente de la foodtruck y empleados asignados -->
                <summary class="flex items-center justify-between px-4 py-3 cursor-pointer select-none group-open:border-b group-open:border-gray-200 group-open:bg-gray-50">
                    <span class="flex flex-wrap items-center gap-3">
                        <span class="uppercase inline-flex items-center px-2.5 py-1 text-xs font-mono font-medium text-gray-700 bg-white border border-gray-200 rounded-md tracking-wider">
                            {{ $foodTruck->license_plate }}
                        </span>

                        <span @class([
                            'inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium border rounded-md',
                            'text-amber-700 bg-amber-50 border-amber-200' => $empleados->count() >= $maxEmpleados,
                            'text-gray-600 bg-white border-gray-200' => $empleados->count() < $maxEmpleados,
                        ])>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a3 3 0 10-2.24-5"></path>
                            </svg>
                            {{ $empleados->count() }}/{{ $maxEmpleados }} empleados
                        </span>
                    </span>

                    <svg class="w-4 h-4 text-gray-400 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </summary>

                <div class="p-4">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Empleados asignados</h3>

                    @forelse($assignments as $assignment)
                        @php($empleado = $assignment->user)
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 px-4 py-3 bg-white border border-gray-200 rounded-lg {{ $loop->last ? '' : 'mb-2' }}">
                            <div class="font-medium text-gray-800">
                                <span class="capitalize">{{ $empleado->name }} {{ $empleado->last_name }}</span>
                            </div>

                            <div class="flex items-center gap-2 sm:justify-end">
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-mono font-medium text-gray-700 bg-gray-100 border border-gray-200 rounded-md tracking-wider">
                                    DNI: {{ $empleado->dni ?? 'Sin DNI' }}
                                </span>

                                <form action="{{ route('admin.asignaciones.destroy', $assignment->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        aria-label="Quitar empleado"
                                        title="Quitar empleado"
                                        class="inline-flex items-center justify-center w-7 h-7 text-base font-semibold leading-none text-red-700 bg-red-50 border border-red-200 rounded-full hover:bg-red-100 active:bg-red-200 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                        -
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-sm text-gray-500 bg-gray-50 border border-dashed border-gray-300 rounded-lg">
                            No hay empleados asignados a esta foodtruck.
                        </div>
                    @endforelse

                    <!-- Botón para agregar empleado -->
                    <form action="#" method="POST" class="mt-3 flex justify-center">
                        @csrf
                        <input type="hidden" name="food_truck_id" value="{{ $foodTruck->id }}">
                        <button
                            type="submit"
                            aria-label="Agregar empleado"
                            title="Agregar empleado"
                            class="inline-flex items-center justify-center w-9 h-9 text-xl font-semibold leading-none text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-full hover:bg-indigo-100 active:bg-indigo-200 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            +
                        </button>
                    </form>
                </div>
            </details>
        @empty
            <div class="p-8 text-center bg-gray-50 rounded-lg border border-dashed border-gray-300">
                <p class="text-gray-500 text-sm">No hay foodtrucks registradas actualmente.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $foodTrucks->links() }}
    </div>
</div>

@endsection
