@extends('admin.index')

@section('title', 'Editar foodtruck')

@section('content')

<div class="max-w-2xl mx-auto my-8 p-6 bg-white rounded-xl shadow-md border border-gray-100">
    <!-- Encabezado de la sección -->
    <div class="pb-4 mb-6 border-b border-gray-200">
        <h1 class="text-2xl font-bold text-gray-800">Editar foodtruck</h1>
        <p class="text-sm text-gray-500">Modificá la patente o el estado de la unidad {{ $foodTruck->license_plate }}.</p>
    </div>

    <!-- Formulario apuntando a update -->
    <form action="{{ route('admin.foodtrucks.update', $foodTruck->id) }}" method="POST" class="space-y-6">
        @csrf

        <!-- Patente -->
        <div>
            <label for="license_plate" class="block text-sm font-medium text-gray-700 mb-1">Patente</label>
            <input
                type="text"
                id="license_plate"
                name="license_plate"
                value="{{ old('license_plate', $foodTruck->license_plate) }}"
                placeholder="Ej: 000ABCD"
                class="w-full px-3.5 py-2.5 text-sm font-mono tracking-wider text-gray-800 bg-gray-50 border border-gray-300 rounded-lg uppercase focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                required
            >
            @error('license_plate')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Estado (acordeón) -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>

            <details class="group bg-gray-50 border border-gray-300 rounded-lg overflow-hidden transition-all duration-200 open:bg-white open:ring-2 open:ring-indigo-500 open:border-indigo-500">
                <summary class="flex items-center justify-between px-4 py-3 cursor-pointer select-none font-medium text-sm text-gray-700 group-open:text-indigo-600 group-open:border-b group-open:border-gray-200">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500 group-open:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path>
                        </svg>
                        Seleccionar estado
                    </span>
                    <svg class="w-4 h-4 text-gray-400 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </summary>

                <div class="p-4 space-y-2">
                    <!-- Habilitado -->
                    <label class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/40 cursor-pointer transition-colors has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/80">
                        <span class="flex items-center gap-2 text-sm font-medium text-gray-800 capitalize">
                            <span class="w-2 h-2 rounded-full bg-green-500" aria-hidden="true"></span>
                            Habilitado
                        </span>
                        <input
                            type="radio"
                            name="status"
                            value="habilitado"
                            class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                            {{ old('status', $foodTruck->status) === 'habilitado' ? 'checked' : '' }}
                            required
                        >
                    </label>

                    <!-- Inhabilitado -->
                    <label class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/40 cursor-pointer transition-colors has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/80">
                        <span class="flex items-center gap-2 text-sm font-medium text-gray-800 capitalize">
                            <span class="w-2 h-2 rounded-full bg-red-500" aria-hidden="true"></span>
                            Inhabilitado
                        </span>
                        <input
                            type="radio"
                            name="status"
                            value="inhabilitado"
                            class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                            {{ old('status', $foodTruck->status) === 'inhabilitado' ? 'checked' : '' }}
                            required
                        >
                    </label>
                </div>
            </details>

            @error('status')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Botones de Acción -->
        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
            <a
                href="/admin/foodtrucks/"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
            >
                Cancelar
            </a>
            <button
                type="submit"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 active:bg-indigo-800 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Actualizar foodtruck
            </button>
        </div>
    </form>
</div>

@endsection
