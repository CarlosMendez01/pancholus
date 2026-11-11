@extends('admin.index')

@section('title', 'Agregar comida')

@section('content')

<div class="max-w-2xl mx-auto my-8 p-6 bg-white rounded-xl shadow-md border border-gray-100">
    <!-- Encabezado de la sección -->
    <div class="pb-4 mb-6 border-b border-gray-200">
        <h1 class="text-2xl font-bold text-gray-800">Agregar comida</h1>
        <p class="text-sm text-gray-500">Registrá una nueva comida con su foto, datos y filtro asociado.</p>
    </div>

    <!-- Formulario apuntando a store -->
    <form action="{{ route('admin.comidas.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Foto -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Foto</label>

            <div class="flex items-start gap-4">
                <!-- Vista previa -->
                <div class="w-32 h-32 shrink-0 bg-gray-200 rounded-lg overflow-hidden border border-gray-200">
                    <div class="w-full h-full flex items-center justify-center" title="Sin foto">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A1.5 1.5 0 0 0 21.75 19.5V4.5A1.5 1.5 0 0 0 20.25 3H3.75A1.5 1.5 0 0 0 2.25 4.5v15A1.5 1.5 0 0 0 3.75 21Z" />
                        </svg>
                    </div>
                </div>

                <!-- Selector de archivo -->
                <div class="flex-1">
                    <input
                        type="file"
                        id="path"
                        name="path"
                        accept="image/*"
                        class="block w-full text-sm text-gray-600 bg-gray-50 border border-gray-300 rounded-lg cursor-pointer file:mr-3 file:px-3 file:py-2.5 file:border-0 file:border-r file:border-gray-300 file:bg-gray-100 file:text-sm file:font-medium file:text-gray-700 hover:file:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                        required
                    >
                    <p class="mt-1 text-xs text-gray-500">Formatos de imagen (JPG, PNG, WEBP). Tamaño máximo: 2 MB.</p>
                    @error('path')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Nombre -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                placeholder="Ej: Hamburguesa completa"
                class="w-full px-3.5 py-2.5 text-sm text-gray-800 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                required
                autofocus
            >
            @error('name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Descripción -->
        <div>
            <label for="details" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
            <textarea
                id="details"
                name="details"
                rows="4"
                placeholder="Ej: Pan, medallón de carne, queso, lechuga y tomate."
                class="w-full px-3.5 py-2.5 text-sm text-gray-800 bg-gray-50 border border-gray-300 rounded-lg resize-y focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                required
            >{{ old('details') }}</textarea>
            @error('details')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Precio -->
        <div>
            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Precio</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-sm text-gray-500 pointer-events-none">$</span>
                <input
                    type="number"
                    id="price"
                    name="price"
                    value="{{ old('price') }}"
                    placeholder="0"
                    min="0"
                    step="0.01"
                    class="w-full pl-8 pr-3.5 py-2.5 text-sm text-gray-800 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                    required
                >
            </div>
            @error('price')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Filtro / categoría -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Filtro</label>

            @if($all_filters->isEmpty())
                <div class="px-3.5 py-2.5 mb-2 text-sm text-gray-500 bg-gray-50 border border-dashed border-gray-300 rounded-lg">
                    No hay filtros registrados en el sistema.
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                @foreach($all_filters as $filter)
                    @php($isCurrent = (string) old('filter_id') === (string) $filter->id)

                    <div class="flex items-center gap-1 pl-3 pr-1.5 py-1.5 text-sm font-medium text-gray-700 bg-gray-50 border border-gray-300 rounded-lg transition-colors hover:border-indigo-300 hover:bg-indigo-50/40 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50 has-[:checked]:text-indigo-700 has-[:checked]:font-semibold">
                        <label class="flex items-center gap-2 flex-1 min-w-0 cursor-pointer">
                            <input
                                type="radio"
                                name="filter_id"
                                value="{{ $filter->id }}"
                                class="w-4 h-4 shrink-0 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                {{ $isCurrent ? 'checked' : '' }}
                                required
                            >
                            <span class="truncate">{{ $filter->name ?? 'Sin filtro' }}</span>
                        </label>

                        <!-- Editar filtro -->
                        <a
                            href="{{ route('admin.filtros.edit', ['filter' => $filter->id, 'return_to' => url()->full()]) }}"
                            title="Editar filtro {{ $filter->name }}"
                            aria-label="Editar filtro {{ $filter->name }}"
                            class="inline-flex items-center justify-center w-7 h-7 shrink-0 text-amber-700 bg-amber-50 border border-amber-200 rounded-md hover:bg-amber-100 transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.062 2.062 0 0 1 2.915 2.915L7.5 19.679l-4.5 1.5 1.5-4.5L16.862 4.487Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 6.75 17.25 9" />
                            </svg>
                        </a>

                        <!-- Eliminar filtro -->
                        <button
                            type="submit"
                            form="delete-filter-{{ $filter->id }}"
                            title="Eliminar filtro {{ $filter->name }}"
                            aria-label="Eliminar filtro {{ $filter->name }}"
                            class="inline-flex items-center justify-center w-7 h-7 shrink-0 text-red-700 bg-red-50 border border-red-200 rounded-md hover:bg-red-100 transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .566c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                    </div>
                @endforeach

                <!-- Nuevo filtro -->
                <a
                    href="{{ route('admin.filtros.create', ['return_to' => url()->full()]) }}"
                    title="Crear un nuevo filtro"
                    class="flex items-center justify-center gap-1.5 px-3 py-2.5 min-h-[46px] text-sm font-semibold text-indigo-700 bg-indigo-50 border border-dashed border-indigo-300 rounded-lg hover:bg-indigo-100 transition-colors"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Nuevo filtro
                </a>
            </div>

            <p class="mt-1 text-xs text-gray-500">Seleccioná el filtro con el que se relacionará la comida.</p>

            @error('filter_id')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Botones de Acción -->
        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
            <a
                href="/admin/comidas/"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
            >
                Cancelar
            </a>
            <button
                type="submit"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 active:bg-indigo-800 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Guardar comida
            </button>
        </div>
    </form>

    {{-- Formularios externos para eliminar filtros: el atributo form de cada
         basurero apunta acá, así no se anidan formularios dentro del de comida. --}}
    @foreach($all_filters as $filter)
        <form
            id="delete-filter-{{ $filter->id }}"
            action="{{ route('admin.filtros.destroy', $filter->id) }}"
            method="POST"
            class="hidden"
        >
            @csrf
            @method('DELETE')
        </form>
    @endforeach
</div>

@endsection
