@extends('empleados.index')

@section('title', 'Inicio')

@section('content')

<div class="max-w-4xl mx-auto">
    <!-- Bienvenida -->
    <div class="rounded-xl border border-gray-100 bg-gradient-to-r from-blue-900 to-blue-700 text-white p-6 mb-6">
        <h1 class="text-2xl font-bold">Hola, {{ auth()->user()->name }}</h1>
        <p class="text-blue-100 mt-1">
            Bienvenido al panel de empleados. Acá podés gestionar los pedidos de los clientes.
        </p>
    </div>

    <!-- Resumen de pedidos -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="rounded-xl border border-amber-200 bg-amber-50 p-5">
            <p class="text-xs font-semibold text-amber-700 uppercase tracking-wider">Pendientes</p>
            <p class="text-3xl font-bold text-amber-700 mt-1">{{ $pendingCount }}</p>
        </div>

        <div class="rounded-xl border border-green-200 bg-green-50 p-5">
            <p class="text-xs font-semibold text-green-700 uppercase tracking-wider">Realizados</p>
            <p class="text-3xl font-bold text-green-700 mt-1">{{ $realizedCount }}</p>
        </div>

        <div class="rounded-xl border border-red-200 bg-red-50 p-5">
            <p class="text-xs font-semibold text-red-700 uppercase tracking-wider">Cancelados</p>
            <p class="text-3xl font-bold text-red-700 mt-1">{{ $cancelledCount }}</p>
        </div>
    </div>

    <!-- Acceso a pedidos -->
    <a href="{{ route('empleado.pedidos.index') }}"
        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
        Ver pedidos pendientes
    </a>
</div>

@endsection