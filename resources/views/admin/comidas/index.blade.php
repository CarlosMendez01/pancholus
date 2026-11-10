@extends('admin.index')

@section('title', 'Comidas')

@section('content')

<div class="max-w-6xl mx-auto my-8 p-6 bg-white rounded-xl shadow-md border border-gray-100">

    <!-- Encabezado de sección -->
    <div class="flex items-center justify-between pb-4 mb-6 border-b border-gray-200">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Comidas</h1>
            <p class="text-sm text-gray-500">Comidas registradas en el sistema</p>
        </div>
    </div>

    <!-- Grilla de comidas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($meals as $meal)
            <!-- Tarjeta de la comida -->
            <div class="bg-white rounded-2xl border-2 border-black/80 shadow-[4px_4px_0px_0px_rgba(0,0,0,0.9)] overflow-hidden flex flex-col">
                <!-- Foto -->
                <div class="w-full h-40 bg-gray-200 overflow-hidden relative border-b-2 border-black/10">
                    @if($meal->path)
                        <img
                            src="{{ asset('storage/' . $meal->path) }}"
                            alt="Foto de {{ $meal->name }}"
                            class="w-full h-full object-cover"
                            loading="lazy"
                        >
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-200" title="Sin foto">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A1.5 1.5 0 0 0 21.75 19.5V4.5A1.5 1.5 0 0 0 20.25 3H3.75A1.5 1.5 0 0 0 2.25 4.5v15A1.5 1.5 0 0 0 3.75 21Z" />
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Cuerpo de la tarjeta -->
                <div class="p-4 flex flex-col gap-2 flex-grow">
                    <!-- Nombre -->
                    <h3 class="text-lg font-black text-gray-900 uppercase tracking-tight leading-tight capitalize">
                        {{ $meal->name }}
                    </h3>

                    <!-- Detalles -->
                    <p class="text-xs font-medium text-gray-600 leading-snug line-clamp-3">
                        {{ $meal->details }}
                    </p>

                    <!-- Precio + acciones -->
                    <div class="flex items-end justify-between gap-2 mt-auto pt-2">
                        <p class="text-xl font-black text-[#C4381C] tracking-tight">
                            ${{ number_format($meal->price, 0, ',', '.') }}
                        </p>

                        <!-- Acciones (esquina inferior derecha) -->
                        <div class="flex items-center gap-2 shrink-0">
                            <!-- Editar -->
                            <a href="/admin/comidas/actualizar-{{ $meal->id }}"
                                title="Editar comida"
                                aria-label="Editar {{ $meal->name }}"
                                class="inline-flex items-center justify-center w-9 h-9 text-amber-700 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.062 2.062 0 0 1 2.915 2.915L7.5 19.679l-4.5 1.5 1.5-4.5L16.862 4.487Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 6.75 17.25 9" />
                                </svg>
                            </a>

                            <!-- Eliminar -->
                            <form action="/admin/comidas/eliminar-{{ $meal->id }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    title="Eliminar comida"
                                    aria-label="Eliminar {{ $meal->name }}"
                                    onclick="return confirm('¿Estás seguro de que quieres eliminar esta comida?')"
                                    class="inline-flex items-center justify-center w-9 h-9 text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .566c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full p-8 text-center bg-gray-50 rounded-lg border border-dashed border-gray-300">
                <p class="text-gray-500 text-sm">No hay comidas registradas actualmente.</p>
            </div>
        @endforelse
    </div>
</div>

@endsection
