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

    <!-- Tabla de comidas -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="py-3 px-4">Foto</th>
                    <th class="py-3 px-4">Nombre</th>
                    <th class="py-3 px-4">Precio</th>
                    <th class="py-3 px-4">Detalles</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($meals as $meal)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <!-- Foto -->
                        <td class="py-3 px-4">
                            @if($meal->path)
                                <img
                                    src="{{ asset('storage/' . $meal->path) }}"
                                    alt="Foto de {{ $meal->name }}"
                                    class="w-16 h-16 object-cover rounded-lg border border-gray-200 shadow-sm"
                                    loading="lazy"
                                >
                            @else
                                <div class="w-16 h-16 flex items-center justify-center bg-gray-100 border border-dashed border-gray-300 rounded-lg" title="Sin foto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A1.5 1.5 0 0 0 21.75 19.5V4.5A1.5 1.5 0 0 0 20.25 3H3.75A1.5 1.5 0 0 0 2.25 4.5v15A1.5 1.5 0 0 0 3.75 21Z" />
                                    </svg>
                                </div>
                            @endif
                        </td>

                        <!-- Nombre -->
                        <td class="py-3 px-4 font-medium text-gray-800 capitalize">
                            {{ $meal->name }}
                        </td>

                        <!-- Precio -->
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-100 rounded-md">
                                $ {{ number_format($meal->price, 2, ',', '.') }}
                            </span>
                        </td>

                        <!-- Detalles -->
                        <td class="py-3 px-4 text-sm text-gray-600 max-w-md">
                            {{ str()->limit($meal->details, 110) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center bg-gray-50 rounded-lg">
                            <p class="text-gray-500 text-sm">No hay comidas registradas actualmente.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
