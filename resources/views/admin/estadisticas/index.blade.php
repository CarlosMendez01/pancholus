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
        style="position: relative; height: 350px;"
    >
        <canvas id="ordersChart"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js"></script>

    <script>
        const statsPanel = document.getElementById('stats-panel');
        const canvas = document.getElementById('ordersChart');

        const chart = new Chart(canvas, {
            type: 'bar',
            data: {
                labels: ['Pendientes', 'Realizados', 'Cancelados'],
                datasets: [{
                    label: 'Cantidad de pedidos',
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

        let statsInFlight = false;

        const renderStats = (data) => {
            chart.data.datasets[0].data = [data.pending, data.completed, data.cancelled];
            chart.update();

            document.getElementById('stats-total').textContent =
                data.pending + data.completed + data.cancelled;
            document.getElementById('stats-updated').textContent =
                'Actualizado ' + new Date().toLocaleTimeString();
        };

        const refreshStats = async () => {
            if (statsInFlight) {
                return;
            }

            statsInFlight = true;

            try {
                const response = await fetch(statsPanel.dataset.refreshUrl, {
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (response.ok) {
                    renderStats(await response.json());
                }
            } catch (error) {
                console.error('No se pudieron actualizar las estadísticas.', error);
            } finally {
                statsInFlight = false;
            }
        };

        renderStats({
            pending: {{ $pending }},
            completed: {{ $completed }},
            cancelled: {{ $cancelled }},
        });

        setInterval(refreshStats, 7000);
    </script>
@endsection
