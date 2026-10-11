@extends('empleados.index')

@section('title', 'Pedidos')

@section('content')

@if(session('success'))
    <div class="mb-4 p-4 text-sm text-green-800 bg-green-100 rounded-lg border border-green-200">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-4 text-sm text-red-800 bg-red-100 rounded-lg border border-red-200">
        {{ session('error') }}
    </div>
@endif

<!-- Encabezado de sección -->
<div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-200">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Pedidos</h1>
        <!--<p class="text-sm text-gray-500">Pedidos de clientes</p>-->
    </div>
</div>

<!-- Panel con auto-refresh -->
<div
    id="pedidos-panel"
    data-refresh-url="{{ route('empleado.pedidos.actualizar') }}"
    data-signature="{{ $signature }}"
>
    @include('empleados.pedidos._lista', ['orders' => $orders, 'status' => $status, 'counts' => $counts])
</div>

@endsection
