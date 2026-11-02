@extends('admin.index')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">
            Estadísticas de pedidos
        </h1>
        <span id="stats-updated" class="text-xs text-gray-500"></span>
    </div>

    <p class="mb-4">
        Total de pedidos: <span id="stats-total">{{ $pending + $completed + $cancelled }}</span>
    </p>

    <div
        id="stats-panel"
        data-refresh-url="{{ route('admin.statistics.data') }}"
        data-pending="{{ $pending }}"
        data-completed="{{ $completed }}"
        data-cancelled="{{ $cancelled }}"
        style="position: relative; height: 350px;"
    >
        <canvas id="ordersChart"></canvas>
    </div>
@endsection
