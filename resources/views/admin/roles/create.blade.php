@extends('admin.index')

@section('content')
<div class="max-w-md mx-auto mt-8 p-6 bg-white rounded-xl shadow-md border border-gray-100">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-gray-800">Crear Nuevo Rol</h2>
        <a href="{{ route('admin.roles.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Volver</a>
    </div>
    
    <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="nuevo_rol" class="block text-sm font-medium text-gray-700 mb-1">
                Nombre del rol
            </label>
            <input 
                type="text" 
                name="nuevo_rol" 
                id="nuevo_rol" 
                value="{{ old('nuevo_rol') }}"
                placeholder="Ej. Administrador, Editor..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors text-gray-800 placeholder-gray-400"
                required
            >
            @error('nuevo_rol')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button 
            type="submit" 
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg shadow transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
            Agregar nuevo rol
        </button>
    </form>
</div>
@endsection