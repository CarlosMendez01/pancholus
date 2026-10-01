@extends('admin.index')
@section('content')

<div class="relative max-w-4xl mx-auto my-8 p-6 bg-white rounded-xl shadow-md border border-gray-100">
    <!-- Encabezado de la sección -->
    <div class="pb-4 mb-6 border-b border-gray-200">
        <h1 class="text-2xl font-bold text-gray-800">Editar empleado</h1>
        <p class="text-sm text-gray-500">Consulta o modifica los datos de {{ $user->name }} {{ $user->last_name }}.</p>
    </div>

    <form action="{{ route('admin.empleados.delete', $user->id) }}" method="POST" class="absolute top-6 right-6">
        @csrf
        @method('DELETE')
        <button 
            type="submit" 
            onclick="return confirm('¿Estás seguro de que deseas dar de baja a este empleado?')"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 hover:text-red-800 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            Dar de baja
        </button>
    </form>

    <!-- Formulario apuntando a update -->
    <form action="{{ route('admin.empleados.update', $user->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('POST')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nombre (Solo lectura) -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Nombre</label>
                <div class="w-full px-3.5 py-2.5 text-sm text-gray-700 bg-gray-100/80 border border-gray-200 rounded-lg select-none font-medium capitalize">
                    {{ $user->name }}
                </div>
            </div>

            <!-- Apellido (Solo lectura) -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Apellido</label>
                <div class="w-full px-3.5 py-2.5 text-sm text-gray-700 bg-gray-100/80 border border-gray-200 rounded-lg select-none font-medium capitalize">
                    {{ $user->last_name }}
                </div>
            </div>

            <!-- DNI (Solo lectura) -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">DNI / Identificación</label>
                <div class="w-full px-3.5 py-2.5 text-sm text-gray-700 bg-gray-100/80 border border-gray-200 rounded-lg select-none font-medium">
                    {{ $user->dni }}
                </div>
            </div>

            <!-- Teléfono (Editable) -->
            <div>
                <label for="telefono_actualizado" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                <input 
                    type="text" 
                    id="telefono_actualizado" 
                    name="telefono_actualizado" 
                    value="{{ $user->phone }}"
                    class="w-full px-3.5 py-2.5 text-sm text-gray-800 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                >
            </div>

            <!-- Correo Electrónico (Editable) -->
            <div>
                <label for="correo_actualizado" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                <input 
                    type="email" 
                    id="correo_actualizado" 
                    name="correo_actualizado" 
                    value="{{ $user->email }}"
                    class="w-full px-3.5 py-2.5 text-sm text-gray-800 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                >
            </div>

            <!-- Contraseña -->
            <div>
                <label for="empleado_contrasena" class="block text-sm font-medium text-gray-700 mb-1">Nueva Contraseña (Opcional)</label>
                <input 
                    type="password" 
                    id="empleado_contrasena" 
                    name="empleado_contrasena" 
                    placeholder="Déjala en blanco si no la deseas cambiar"
                    class="w-full px-3.5 py-2.5 text-sm text-gray-800 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                >
            </div>
        </div>

        <!-- Selección de Rol -->
        <div class="pt-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Asignar Rol de Usuario</label>
            
            @forelse($roles as $rol)
                @if($loop->first)
                    <details class="group bg-gray-50 border border-gray-300 rounded-lg overflow-hidden transition-all duration-200 open:bg-white open:ring-2 open:ring-indigo-500 open:border-indigo-500">
                        <summary class="flex items-center justify-between px-4 py-3 cursor-pointer select-none font-medium text-sm text-gray-700 group-open:text-indigo-600 group-open:border-b group-open:border-gray-200">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500 group-open:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Seleccionar rol de usuario
                            </span>
                            <svg class="w-4 h-4 text-gray-400 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>

                        <div class="p-4 space-y-2 max-h-60 overflow-y-auto">
                @endif

                            <label class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/40 cursor-pointer transition-colors has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/80">
                                <span class="text-sm font-medium text-gray-800 capitalize">
                                    {{ $rol->role }}
                                </span>
                                <input 
                                    type="radio" 
                                    name="rol_actualizado" 
                                    value="{{ $rol->id }}" 
                                    class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                    {{ $user->role_id == $rol->id ? 'checked' : '' }}
                                    required
                                >
                            </label>

                @if($loop->last)
                        </div>
                    </details>
                @endif
            @empty
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg flex items-start gap-3">
                    <p class="text-sm text-amber-800">No hay roles registrados en el sistema.</p>
                </div>
            @endforelse
        </div>

        <!-- Botones de Acción -->
        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
            <a 
                href="{{ route('admin.empleados.index') }}" 
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
            >
                Cancelar
            </a>
            <button 
                type="submit" 
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 active:bg-indigo-800 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Actualizar empleado
            </button>
        </div>
    </form>
</div>

@endsection