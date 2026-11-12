@extends('admin.index')

@section('title', 'Puntos de Venta')

@section('content')

<div class="max-w-6xl mx-auto my-8 p-6 bg-white rounded-xl shadow-md border border-gray-100">

    <!-- Encabezado de sección -->
    <div class="flex items-center justify-between pb-4 mb-6 border-b border-gray-200">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Puntos de Venta</h1>
            <p class="text-sm text-gray-500">Ubicaciones registradas y su foodtruck asignada</p>
        </div>

        <a href="{{ route('admin.pdvs.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 active:bg-indigo-800 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Agregar punto de venta
        </a>
    </div>

    <!-- Contenedor de lista / tabla -->
    <div class="space-y-3">
        <!-- Encabezados para escritorio -->
        <div class="hidden md:grid md:grid-cols-5 gap-4 px-4 py-2 bg-gray-50 rounded-lg text-xs font-semibold text-gray-500 uppercase tracking-wider">
            <div>Punto de venta</div>
            <div>Latitud</div>
            <div>Longitud</div>
            <div>Foodtruck</div>
            <div class="text-right">Acciones</div>
        </div>

        @forelse($pdvAssignments as $assignment)
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center p-4 bg-white border border-gray-200 rounded-lg hover:shadow-sm transition-shadow">
                <!-- Identificador del punto de venta -->
                <div>
                    <span class="md:hidden font-semibold text-gray-500 block text-xs uppercase mb-1">Punto de venta:</span>
                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-mono font-medium text-gray-700 bg-gray-100 border border-gray-200 rounded-md tracking-wider">
                        #{{ $assignment->pdv?->id ?? 'Sin ID' }}
                    </span>
                </div>

                <!-- Latitud -->
                <div>
                    <span class="md:hidden font-semibold text-gray-500 block text-xs uppercase mb-1">Latitud:</span>
                    <span class="font-mono text-sm text-gray-700">
                        {{ $assignment->pdv?->latitude ?? 'Sin latitud' }}
                    </span>
                </div>

                <!-- Longitud -->
                <div>
                    <span class="md:hidden font-semibold text-gray-500 block text-xs uppercase mb-1">Longitud:</span>
                    <span class="font-mono text-sm text-gray-700">
                        {{ $assignment->pdv?->longitude ?? 'Sin longitud' }}
                    </span>
                </div>

                <!-- Foodtruck asociada -->
                <div>
                    <span class="md:hidden font-semibold text-gray-500 block text-xs uppercase mb-1">Foodtruck:</span>
                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-mono font-medium text-gray-700 bg-gray-100 border border-gray-200 rounded-md tracking-wider">
                        {{ $assignment->foodTruck?->license_plate ?? '-' }}
                    </span>
                </div>

                <!-- Acciones -->
                <div class="md:text-right pt-2 md:pt-0 border-t md:border-t-0 border-gray-100">
                    <div class="flex items-center md:justify-end gap-2">
                        <!-- Editar -->
                        <a href="{{ route('admin.pdvs.edit', $assignment->id) }}"
                            title="Editar punto de venta"
                            aria-label="Editar punto de venta"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-amber-700 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.062 2.062 0 0 1 2.915 2.915L7.5 19.679l-4.5 1.5 1.5-4.5L16.862 4.487Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 6.75 17.25 9" />
                            </svg>
                            Editar
                        </a>

                        <!-- Eliminar -->
                        <form action="{{ route('admin.pdvs.destroy', $assignment->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                title="Eliminar punto de venta"
                                aria-label="Eliminar punto de venta"
                                onclick="return confirm('¿Estás seguro de que quieres eliminar este punto de venta?')"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .566c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-8 text-center bg-gray-50 rounded-lg border border-dashed border-gray-300">
                <p class="text-gray-500 text-sm">No hay puntos de venta registrados actualmente.</p>
            </div>
        @endforelse
    </div>
</div>

@endsection
