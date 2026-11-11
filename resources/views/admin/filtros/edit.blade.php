@extends('admin.index')

@section('title', 'Editar filtro')

@section('content')

@php
    $returnTo = old('return_to', request('return_to'));

    if (! is_string($returnTo) || $returnTo === ''
        || (! str_starts_with($returnTo, url('/admin/comidas/')) && ! str_starts_with($returnTo, '/admin/comidas/'))) {
        $returnTo = route('admin.comidas.list');
    }
@endphp

<div class="max-w-lg mx-auto my-8 p-6 bg-white rounded-xl shadow-md border border-gray-100">
    <!-- Encabezado de la sección -->
    <div class="pb-4 mb-6 border-b border-gray-200">
        <h1 class="text-2xl font-bold text-gray-800">Editar filtro</h1>
        <p class="text-sm text-gray-500">Modificá el nombre actual del filtro.</p>
    </div>

    <!-- Formulario apuntando a update -->
    <form action="{{ route('admin.filtros.update', $filter) }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="return_to" value="{{ $returnTo }}">

        <!-- Nombre actual -->
        <div>
            <p class="mb-3 text-sm text-gray-500">
                Nombre actual:
                <span class="font-semibold text-gray-700">{{ $filter->name ?? 'Sin filtro' }}</span>
            </p>

            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nuevo nombre</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $filter->name) }}"
                placeholder="Ej: combos"
                maxlength="255"
                class="w-full px-3.5 py-2.5 text-sm text-gray-800 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                required
                autofocus
            >
            @error('name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Botones de Acción -->
        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
            <a
                href="{{ $returnTo }}"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
            >
                Cancelar
            </a>
            <button
                type="submit"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 active:bg-indigo-800 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Actualizar filtro
            </button>
        </div>
    </form>
</div>

@endsection
