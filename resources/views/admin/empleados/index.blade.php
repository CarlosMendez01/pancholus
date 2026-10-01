@extends('admin.index')
@section('content')


<div class="max-w-6xl mx-auto my-8 p-6 bg-white rounded-xl shadow-md border border-gray-100">

    @if(session('success'))
        <div class="mb-4 p-4 text-sm text-green-800 bg-green-100 rounded-lg border border-green-200">
            {{ session('success') }}
        </div>
    @endif
    <!-- Encabezado de sección -->
    <div class="flex items-center justify-between pb-4 mb-6 border-b border-gray-200">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Empleados</h1>
            <p class="text-sm text-gray-500">Gestión y nómina del personal registrado</p>
        </div>

        <a href="{{ route('admin.empleados.create') }}">
            <button class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 active:bg-indigo-800 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Agregar empleado
            </button>
        </a>
    </div>

    <!-- Contenedor de lista / tabla -->
    <div class="space-y-3">
        <!-- Encabezados para escritorio -->
        <div class="hidden md:grid md:grid-cols-5 gap-4 px-4 py-2 bg-gray-50 rounded-lg text-xs font-semibold text-gray-500 uppercase tracking-wider">
            <div>Nombre</div>
            <div>Apellido</div>
            <div>Área / Rol</div>
            <div>DNI</div>
            <div class="text-right">Acciones</div>
        </div>

        @forelse($users as $user)
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center p-4 bg-white border border-gray-200 rounded-lg hover:shadow-sm transition-shadow">
                <!-- Nombre -->
                <div class="font-medium text-gray-800">
                    <span class="md:hidden font-semibold text-gray-500 block text-xs uppercase">Nombre:</span>
                    <p class="capitalize">{{ $user->name }}</p>
                </div>

                <!-- Apellido -->
                <div class="font-medium text-gray-800">
                    <span class="md:hidden font-semibold text-gray-500 block text-xs uppercase">Apellido:</span>
                    <p class="capitalize">{{ $user->last_name }}</p>
                </div>

                <!-- Área / Rol -->
                <div class="text-gray-600">
                    <span class="md:hidden font-semibold text-gray-500 block text-xs uppercase">Área:</span>
                    @foreach($roles as $rol)
                        @if($user->role_id === $rol->id)
                            <p class="capitalize">{{ $rol->role }}</p>
                        @endif
                    @endforeach
                </div>

                <!-- DNI -->
                <div>
                    <span class="md:hidden font-semibold text-gray-500 block text-xs uppercase mb-1">DNI:</span>
                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-mono font-medium text-gray-700 bg-gray-100 border border-gray-200 rounded-md tracking-wider">
                        {{ $user->dni ?? 'Sin DNI' }}
                    </span>
                </div>

                <!-- Acciones -->
                <div class="md:text-right pt-2 md:pt-0 border-t md:border-t-0 border-gray-100">
                    <a href="{{ route('admin.empleados.edit', $user->id) }}" class="inline-block px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition-colors">
                        Más info
                    </a>
                </div>
            </div>
        @empty
            <div class="p-8 text-center bg-gray-50 rounded-lg border border-dashed border-gray-300">
                <p class="text-gray-500 text-sm">No hay usuarios registrados actualmente.</p>
            </div>
        @endforelse
    </div>    
</div>

@endsection