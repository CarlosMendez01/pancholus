@extends('admin.index')

@section('title', 'Editar punto de venta')

@section('content')

<div class="max-w-2xl mx-auto my-8 p-6 bg-white rounded-xl shadow-md border border-gray-100">
    <!-- Encabezado de la sección -->
    <div class="pb-4 mb-6 border-b border-gray-200">
        <h1 class="text-2xl font-bold text-gray-800">Editar punto de venta</h1>
        <p class="text-sm text-gray-500">
            Modificá la ubicación y la foodtruck asignada al punto de venta #{{ $assignment->pdv?->id ?? $assignment->id }}.
        </p>
    </div>

    <!-- Formulario apuntando a update -->
    <form action="{{ route('admin.pdvs.update', $assignment) }}" method="POST" class="space-y-6">
        @csrf

        <!-- Coordenadas -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Latitud -->
            <div>
                <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">Latitud</label>
                <input
                    type="number"
                    id="latitude"
                    name="latitude"
                    value="{{ old('latitude', $assignment->pdv?->latitude) }}"
                    placeholder="Ej: -34.603722"
                    step="any"
                    min="-90"
                    max="90"
                    inputmode="decimal"
                    class="w-full px-3.5 py-2.5 text-sm font-mono text-gray-800 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                    required
                >
                @error('latitude')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Longitud -->
            <div>
                <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">Longitud</label>
                <input
                    type="number"
                    id="longitude"
                    name="longitude"
                    value="{{ old('longitude', $assignment->pdv?->longitude) }}"
                    placeholder="Ej: -58.381592"
                    step="any"
                    min="-180"
                    max="180"
                    inputmode="decimal"
                    class="w-full px-3.5 py-2.5 text-sm font-mono text-gray-800 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                    required
                >
                @error('longitude')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Foodtruck asignada -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Foodtruck asignada</label>

            @if($foodTrucks->isEmpty())
                <div class="px-3.5 py-2.5 text-sm text-gray-500 bg-gray-50 border border-dashed border-gray-300 rounded-lg">
                    No hay foodtrucks registradas en el sistema.
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    @foreach($foodTrucks as $foodTruck)
                        @php($isDisabled = $foodTruck->status !== 'habilitado')
                        @php($isCurrent = (string) old('food_truck_id', $assignment->food_truck_id) === (string) $foodTruck->id)

                        <label @class([
                            'flex items-center gap-2 px-3 py-2.5 text-sm font-medium bg-gray-50 border border-gray-300 rounded-lg transition-colors',
                            'text-gray-700 cursor-pointer hover:border-indigo-300 hover:bg-indigo-50/40 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50 has-[:checked]:text-indigo-700 has-[:checked]:font-semibold' => ! $isDisabled,
                            'text-gray-400 cursor-not-allowed opacity-60 select-none' => $isDisabled,
                        ])>
                            <input
                                type="radio"
                                name="food_truck_id"
                                value="{{ $foodTruck->id }}"
                                class="w-4 h-4 shrink-0 text-indigo-600 border-gray-300 focus:ring-indigo-500 disabled:cursor-not-allowed"
                                {{ $isCurrent ? 'checked' : '' }}
                                @disabled($isDisabled)
                                required
                            >
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-mono font-medium bg-white border border-gray-200 rounded-md tracking-wider">
                                {{ $foodTruck->license_plate }}
                            </span>
                            @if($isDisabled)
                                <span class="ml-auto inline-flex items-center gap-1.5 px-2 py-0.5 text-[11px] font-semibold text-red-700 bg-red-100 border border-red-200 rounded-md">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500" aria-hidden="true"></span>
                                    Inhabilitado
                                </span>
                            @endif
                        </label>
                    @endforeach
                </div>

                <p class="mt-1 text-xs text-gray-500">La foodtruck resaltada es la que está asignada actualmente a este punto de venta. Las foodtrucks inhabilitadas solo se muestran y no pueden seleccionarse.</p>
            @endif

            @error('food_truck_id')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Botones de Acción -->
        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
            <a
                href="{{ route('admin.pdvs.index') }}"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
            >
                Cancelar
            </a>
            <button
                type="submit"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 active:bg-indigo-800 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Actualizar punto de venta
            </button>
        </div>
    </form>
</div>

@endsection
