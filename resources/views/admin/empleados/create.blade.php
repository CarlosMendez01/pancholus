@extends('admin.index')
@section('content')

<div class="max-w-4xl mx-auto my-8 p-6 bg-white rounded-xl shadow-md border border-gray-100">
    <!-- Encabezado de la sección -->
    <div class="pb-4 mb-6 border-b border-gray-200">
        <h1 class="text-2xl font-bold text-gray-800">Nuevo empleado</h1>
        <p class="text-sm text-gray-500">Ingresa los datos personales para registrar al nuevo usuario en el sistema.</p>
    </div>

    <!-- Formulario -->
    <form action="{{ route('admin.empleados.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nombre -->
            <div>
                <label for="empleado_nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                <input 
                    type="text" 
                    id="empleado_nombre" 
                    name="empleado_nombre" 
                    value="{{ old('empleado_nombre') }}"
                    placeholder="Ej. Juan"
                    class="w-full px-3.5 py-2.5 text-sm text-gray-800 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                >
            </div>

            <!-- Apellido -->
            <div>
                <label for="empleado_apellido" class="block text-sm font-medium text-gray-700 mb-1">Apellido</label>
                <input 
                    type="text" 
                    id="empleado_apellido" 
                    name="empleado_apellido" 
                    value="{{ old('empleado_apellido') }}"
                    placeholder="Ej. Pérez"
                    class="w-full px-3.5 py-2.5 text-sm text-gray-800 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                >
            </div>

            <!-- DNI -->
            <div>
                <label for="empleado_dni" class="block text-sm font-medium text-gray-700 mb-1">DNI / Identificación</label>
                <input 
                    type="text" 
                    id="empleado_dni" 
                    name="empleado_dni" 
                    value="{{ old('empleado_dni') }}"
                    placeholder="Ej. 12345678"
                    class="w-full px-3.5 py-2.5 text-sm text-gray-800 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                >
            </div>

            <!-- Teléfono -->
            <div>
                <label for="empleado_telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                <input 
                    type="text" 
                    id="empleado_telefono" 
                    name="empleado_telefono" 
                    value="{{ old('empleado_telefono') }}"
                    placeholder="Ej. +54 9 11 1234-5678"
                    class="w-full px-3.5 py-2.5 text-sm text-gray-800 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                >
            </div>

            <!-- Correo Electrónico -->
            <div>
                <label for="empleado_correo" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                <input 
                    type="email" 
                    id="empleado_correo" 
                    name="empleado_correo" 
                    value="{{ old('empleado_correo') }}"
                    placeholder="ejemplo@correo.com"
                    class="w-full px-3.5 py-2.5 text-sm text-gray-800 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                >
            </div>

            <!-- Contraseña -->
            <div>
                <label for="empleado_contrasena" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                <input 
                    type="password" 
                    id="empleado_contrasena" 
                    name="empleado_contrasena" 
                    placeholder="••••••••"
                    class="w-full px-3.5 py-2.5 text-sm text-gray-800 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                >
            </div>
        </div>

        <!-- Selección de Rol estilo Acordeón / Desplegable -->
        <div class="pt-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Asignar Rol de Usuario</label>
            
            @forelse($roles as $rol)
                @if($loop->first)
                    <!-- Acordeón / Collapsible nativo con HTML <details> -->
                    <details class="group bg-gray-50 border border-gray-300 rounded-lg overflow-hidden transition-all duration-200 open:bg-white open:ring-2 open:ring-indigo-500 open:border-indigo-500">
                        <summary class="flex items-center justify-between px-4 py-3 cursor-pointer select-none font-medium text-sm text-gray-700 group-open:text-indigo-600 group-open:border-b group-open:border-gray-200">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500 group-open:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Desplegar opciones para seleccionar rol
                            </span>
                            <svg class="w-4 h-4 text-gray-400 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>

                        <div class="p-4 space-y-2 max-h-60 overflow-y-auto">
                @endif

                            <!-- Opción de Radio Button dentro del acordeón -->
                            <label class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/40 cursor-pointer transition-colors has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/80">
                                <span class="text-sm font-medium text-gray-800 capitalize">
                                    <p>{{ $rol->role }}</p>
                                </span>
                                <input 
                                    type="radio" 
                                    name="rol_id" 
                                    value="{{ $rol->id }}" 
                                    class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                    required
                                >
                            </label>

                @if($loop->last)
                        </div>
                    </details>
                @endif
            @empty
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div class="text-sm text-amber-800">
                        <p class="font-medium">Al parecer no existen roles creados.</p>
                        <p class="mt-0.5">Debes crear al menos un rol de usuario en el sistema antes de poder agregar un nuevo empleado.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Botones de Acción -->
        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
            <a 
                href="/admin/empleados" 
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
            >
                Cancelar
            </a>
            <button 
                type="submit" 
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 active:bg-indigo-800 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Agregar empleado
            </button>
        </div>
    </form>
</div>

@endsection