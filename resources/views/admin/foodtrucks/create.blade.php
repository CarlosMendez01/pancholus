@extends('admin.index')

@section('title', 'Agregar foodtruck')

@section('content')

<div class="max-w-2xl mx-auto my-8 p-6 bg-white rounded-xl shadow-md border border-gray-100">
    <!-- Encabezado de la sección -->
    <div class="pb-4 mb-6 border-b border-gray-200">
        <h1 class="text-2xl font-bold text-gray-800">Agregar foodtruck</h1>
        <p class="text-sm text-gray-500">Registrá una nueva unidad en el sistema.</p>
    </div>

    <!-- Formulario apuntando a store -->
    <form action="{{ route('admin.foodtrucks.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Matrícula -->
        <div>
            <label for="license_plate" class="block text-sm font-medium text-gray-700 mb-1">Matrícula</label>
            <input
                type="text"
                id="license_plate"
                name="license_plate"
                value="{{ old('license_plate') }}"
                placeholder="Ej: 000ABCD"
                class="w-full px-3.5 py-2.5 text-sm font-mono tracking-wider text-gray-800 bg-gray-50 border border-gray-300 rounded-lg uppercase focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                required
                autofocus
            >
            @error('license_plate')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Estado por defecto -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
            <div class="flex items-center gap-2 px-3.5 py-2.5 text-sm text-gray-600 bg-gray-100/80 border border-gray-200 rounded-lg select-none">
                <span class="w-2 h-2 rounded-full bg-green-500" aria-hidden="true"></span>
                <span class="capitalize">Habilitado</span>
                <span class="text-xs text-gray-400">(por defecto)</span>
            </div>
            <p class="mt-1 text-xs text-gray-500">La foodtruck se registra habilitada; podés inhabilitarla desde el listado.</p>
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
                Guardar foodtruck
            </button>
        </div>
    </form>
</div>

@endsection
