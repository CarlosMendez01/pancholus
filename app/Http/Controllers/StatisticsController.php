<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class StatisticsController extends Controller
{
    public function index()
    {
        $pending = Order::where('status', 'pendiente')->count();
        $completed = Order::where('status', 'realizado')->count();
        $cancelled = Order::where('status', 'cancelado')->count();

        return view('admin.estadisticas.index', compact(
            'pending',
            'completed',
            'cancelled'
        ));
    }
}
