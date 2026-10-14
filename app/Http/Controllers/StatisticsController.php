<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class StatisticsController extends Controller
{
    /**
     * Muestra el panel de estadísticas de pedidos.
     */
    public function index(): View
    {
        return view('admin.estadisticas.index', $this->orderStats());
    }

    /**
     * Devuelve los contadores de pedidos para el auto-refresh.
     */
    public function data(): JsonResponse
    {
        return response()->json($this->orderStats());
    }

    /**
     * Contadores de pedidos agrupados por estado.
     *
     * @return array<string, int>
     */
    private function orderStats(): array
    {
        $counts = Order::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'pending' => (int) ($counts[Order::STATUS_PENDIENTE] ?? 0),
            'completed' => (int) ($counts[Order::STATUS_REALIZADO] ?? 0),
            'cancelled' => (int) ($counts[Order::STATUS_CANCELADO] ?? 0),
        ];
    }
}
