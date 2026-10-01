@extends('admin.index')

@section('content')
    <h1 class = "text-2x1 font-bold mb-4">
        Estadísticas de pedidos
    </h1>

    <p class = "mb-4">
        Total de pedidos: {{ $pending + $completed + $cancelled }}
    </p>

    <div style = "position: relative; height: 350px;">
        <canvas id = "ordersChart"></canvas>
    </div>

    <script src = "https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js"></script>

    <script>
        const canvas = document.getElementById('ordersChart');

        new Chart(canvas, {
            type: 'bar',
            data: {
                labels: ['Pendientes', 'Realizados', 'Cancelados'],
                datasets: [{
                    label : 'Cantidad de pedidos',
                    data: [
                        {{ $pending }},
                        {{ $completed }},
                        {{ $cancelled }}
                    ],
                    backgroundColor: [
                        '#f59e0b',
                        '#22c55e',
                        '#ef4444'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
@endsection
