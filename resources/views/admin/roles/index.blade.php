@extends('admin.index')

@section('content')
<div class="max-w-4xl mx-auto my-6 p-6 bg-white rounded-lg shadow-md">
    
    <!-- Alerta de Éxito (session flash) -->
    @if(session('success'))
        <div class="mb-4 p-4 text-sm text-green-800 bg-green-100 rounded-lg border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <!-- Encabezado -->
    <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-200">
        <h3 class="text-xl font-bold text-gray-800">Gestión de Roles</h3>
        <a href="{{ route('admin.roles.create') }}" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 transition-colors shadow-sm">
            + Crear rol
        </a>
    </div>

    <!-- Lista / Tabla de Roles -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="py-3 px-4">Rol</th>
                    <th class="py-3 px-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($roles as $rol)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 font-medium text-gray-700 capitalize">
                            {{ $rol->role }}
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <!-- Botón Editar -->
                                <a href="{{ route('admin.roles.edit', $rol->id) }}" class="px-3 py-1 text-xs font-medium text-amber-700 bg-amber-100 rounded-md hover:bg-amber-200 transition-colors">
                                    Editar
                                </a>

                                <!-- Formulario Eliminar -->
                                <form action="{{ route('admin.roles.delete', $rol->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="submit" 
                                        onclick="return confirm('¿Estás seguro de que quieres eliminar este rol?')"
                                        class="px-3 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-md hover:bg-red-200 transition-colors">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="py-6 text-center text-gray-500 text-sm">
                            No hay roles registrados actualmente.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection