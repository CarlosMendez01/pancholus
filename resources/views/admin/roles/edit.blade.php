@extends('admin.index')
@section('content')

<div class="max-w-md mx-auto mt-8 p-6 bg-white rounded-xl shadow-md border border-gray-100">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Editar rol</h2>
    
    <form action="{{ route('admin.roles.update', $rol->id) }}" method="POST" class="space-y-4">
        @csrf
        
        <div>
            <label for="rol_actualizado" class="block text-sm font-medium text-gray-700 mb-1">
                Nombre del rol
            </label>
            <input 
                type="text" 
                id="rol_actualizado"
                name="rol_actualizado"
                value="{{ $rol->role }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
            />
        </div>

        <button 
            type="submit" 
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition duration-200"
        >
            Actualizar
        </button>
    </form>
</div>

@endsection