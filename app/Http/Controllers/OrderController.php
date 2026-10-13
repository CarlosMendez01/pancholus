<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Meal;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Muestra el menú de comidas y el carrito del cliente.
     */
    public function menu(): View
    {
        $meals = Meal::orderBy('name')->get();
        $cart = session('cart', []);

        return view('clientes.menu', compact('meals', 'cart'));
    }

    /**
     * Agrega una comida al carrito de la sesión.
     */
    public function addToCart(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'meal_id' => ['required', 'integer', 'exists:meals,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = session('cart', []);
        $cart[$data['meal_id']] = ($cart[$data['meal_id']] ?? 0) + $data['quantity'];
        session(['cart' => $cart]);

        return back()->with('success', 'Comida agregada al carrito.');
    }

    /**
     * Quita una comida del carrito de la sesión.
     */
    public function removeFromCart(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'meal_id' => ['required', 'integer', 'exists:meals,id'],
        ]);

        $cart = session('cart', []);
        unset($cart[$data['meal_id']]);
        session(['cart' => $cart]);

        return back();
    }

    /**
     * Persiste el pedido del cliente con sus líneas de detalle.
     */
    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $items = $request->validated('items');

        $order = DB::transaction(function () use ($items): Order {
            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => Order::STATUS_PENDIENTE,
            ]);

            foreach ($items as $item) {
                $order->details()->create([
                    'meal_id' => $item['meal_id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            return $order;
        });

        session()->forget('cart');

        return redirect()
            ->route('cliente.pedidos.index')
            ->with('success', "Pedido #{$order->id} creado exitosamente.");
    }

    /**
     * Muestra los pedidos del cliente autenticado.
     */
    public function myOrders(): View
    {
        $orders = Auth::user()
            ->orders()
            ->with('details.meal')
            ->latest()
            ->get();

        return view('clientes.pedidos', compact('orders'));
    }

    /**
     * Muestra el dashboard de bienvenida del empleado.
     */
    public function home(): View
    {
        $pendingCount = Order::where('status', Order::STATUS_PENDIENTE)->count();
        $realizedCount = Order::where('status', Order::STATUS_REALIZADO)->count();
        $cancelledCount = Order::where('status', Order::STATUS_CANCELADO)->count();

        return view('empleados.home', compact('pendingCount', 'realizedCount', 'cancelledCount'));
    }

    /**
     * Lista los pedidos para el empleado, filtrados por estado.
     */
    public function manage(Request $request): View
    {
        $status = $this->resolveStatus($request->query('estado'));
        $orders = $this->paginatedOrders($status);
        $counts = $this->orderCounts();
        $signature = $this->orderSignature($counts);

        return view('empleados.pedidos.index', compact('orders', 'status', 'counts', 'signature'));
    }

    /**
     * Devuelve los pedidos y contadores actualizados para el auto-refresh.
     */
    public function refresh(Request $request): JsonResponse
    {
        $status = $this->resolveStatus($request->query('estado'));
        $orders = $this->paginatedOrders($status);
        $counts = $this->orderCounts();

        return response()->json([
            'signature' => $this->orderSignature($counts),
            'counts' => $counts,
            'html' => view('empleados.pedidos._lista', compact('orders', 'status', 'counts'))->render(),
        ]);
    }

    /**
     * Normaliza el estado recibido, evitando valores fuera de la lista permitida.
     */
    private function resolveStatus(?string $status): string
    {
        $validStatuses = [
            Order::STATUS_PENDIENTE,
            Order::STATUS_REALIZADO,
            Order::STATUS_CANCELADO,
            'todos',
        ];

        return in_array($status, $validStatuses, true) ? $status : Order::STATUS_PENDIENTE;
    }

    /**
     * Consulta paginada de pedidos filtrada por estado, con la ruta de paginación correcta.
     */
    private function paginatedOrders(string $status): LengthAwarePaginator
    {
        return Order::with(['user', 'details.meal'])
            ->when($status !== 'todos', fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString()
            ->withPath(route('empleado.pedidos.index'));
    }

    /**
     * Conteo de pedidos agrupado por estado.
     */
    private function orderCounts(): Collection
    {
        return Order::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->map(fn ($total) => (int) $total);
    }

    /**
     * Firma que cambia cuando se crea o modifica cualquier pedido.
     */
    private function orderSignature(?Collection $counts = null): string
    {
        $counts ??= $this->orderCounts();

        return implode('|', [
            (string) (Order::max('updated_at') ?? ''),
            (string) Order::count(),
            (string) ($counts[Order::STATUS_PENDIENTE] ?? 0),
            (string) ($counts[Order::STATUS_REALIZADO] ?? 0),
            (string) ($counts[Order::STATUS_CANCELADO] ?? 0),
        ]);
    }

    /**
     * Actualiza el estado de un pedido pendiente.
     */
    public function updateStatus(UpdateOrderRequest $request, Order $order): RedirectResponse
    {
        if ($order->status !== Order::STATUS_PENDIENTE) {
            return back()->with('error', 'Solo se pueden modificar pedidos pendientes.');
        }

        $order->update(['status' => $request->validated('status')]);

        return back()->with('success', "Pedido #{$order->id} actualizado a {$order->status}.");
    }
}
