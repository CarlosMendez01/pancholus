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
