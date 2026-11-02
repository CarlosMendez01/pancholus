const pedidosPanel = document.getElementById('pedidos-panel');

if (pedidosPanel) {
    const refreshUrl = pedidosPanel.dataset.refreshUrl;
    let signature = pedidosPanel.dataset.signature;
    let controller = null;
    let inFlight = false;

    const updatePanel = async () => {
        if (inFlight) {
            return;
        }

        inFlight = true;
        controller = new AbortController();

        try {
            const response = await fetch(`${refreshUrl}${window.location.search}`, {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                signal: controller.signal,
            });

            if (!response.ok) {
                return;
            }

            const data = await response.json();

            if (data.signature !== signature) {
                signature = data.signature;
                pedidosPanel.dataset.signature = signature;
                pedidosPanel.innerHTML = data.html;
            }
        } catch (error) {
            if (error.name !== 'AbortError') {
                console.error('No se pudo actualizar el panel de pedidos.', error);
            }
        } finally {
            inFlight = false;
        }
    };

    setInterval(updatePanel, 7000);
}

const statsPanel = document.getElementById('stats-panel');

if (statsPanel) {
    const canvas = document.getElementById('ordersChart');
    const refreshUrl = statsPanel.dataset.refreshUrl;
    const initialData = {
        pending: Number(statsPanel.dataset.pending),
        completed: Number(statsPanel.dataset.completed),
        cancelled: Number(statsPanel.dataset.cancelled),
    };

    let statsInFlight = false;

    const renderStats = (chart, data) => {
        chart.data.datasets[0].data = [data.pending, data.completed, data.cancelled];
        chart.update();

        document.getElementById('stats-total').textContent =
            data.pending + data.completed + data.cancelled;
        document.getElementById('stats-updated').textContent =
            'Actualizado ' + new Date().toLocaleTimeString();
    };

    const refreshStats = async (chart) => {
        if (statsInFlight) {
            return;
        }

        statsInFlight = true;

        try {
            const response = await fetch(refreshUrl, {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (response.ok) {
                renderStats(chart, await response.json());
            }
        } catch (error) {
            console.error('No se pudieron actualizar las estadísticas.', error);
        } finally {
            statsInFlight = false;
        }
    };

    import('chart.js/auto').then(({ default: Chart }) => {
        const chart = new Chart(canvas, {
            type: 'bar',
            data: {
                labels: ['Pendientes', 'Realizados', 'Cancelados'],
                datasets: [{
                    label: 'Cantidad de pedidos',
                    data: [
                        initialData.pending,
                        initialData.completed,
                        initialData.cancelled,
                    ],
                    backgroundColor: [
                        '#f59e0b',
                        '#22c55e',
                        '#ef4444',
                    ],
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                        },
                    },
                },
            },
        });

        renderStats(chart, initialData);

        setInterval(() => refreshStats(chart), 7000);
    });
}
