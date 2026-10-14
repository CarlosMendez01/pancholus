<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Meal;
use App\Models\Order;
use App\Services\KernelPedidos;
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
    public function __construct(private readonly KernelPedidos $kernel) {}

    /**
     * Muestra el carrito del cliente resuelto desde la sesión.
     */
    public function cart(): View
    {
        $cart = session('cart', []);
        $meals = Meal::whereIn('id', array_keys($cart))->get()->keyBy('id');

        $items = [];

        foreach ($cart as $mealId => $quantity) {
            $meal = $meals->get($mealId);

            if (! $meal) {
                continue;
            }

            $items[] = [
                'id' => $meal->id,
                'name' => $meal->name,
                'price' => $meal->price,
                'quantity' => $quantity,
            ];
        }

        return view('clientes.carrito', compact('items'));
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
        $cart = $this->kernel->acumularCarrito($cart, $data['meal_id'], $data['quantity']);
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
        return $this->kernel->normalizarFiltro($status);
    }

    /**
     * Consulta paginada de pedidos filtrada por estado, con la ruta de paginación correcta.
     */
    private function paginatedOrders(string $status): LengthAwarePaginator
    {
        return Order::with(['user', 'details.meal'])
            ->when($status !== KernelPedidos::FILTRO_TODOS, fn ($query) => $query->where('status', $status))
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
        if (! $this->kernel->puedeModificar($order->status)) {
            return back()->with('error', 'Solo se pueden modificar pedidos pendientes.');
        }

        $order->update(['status' => $request->validated('status')]);

        return back()->with('success', "Pedido #{$order->id} actualizado.");
    }

    /**
     * Da de baja lógica un pedido finalizado (realizado o cancelado).
     */
    public function destroy(Order $order): RedirectResponse
    {
        if (! $this->kernel->puedeEliminar($order->status)) {
            return back()->with('error', 'Solo se pueden eliminar pedidos finalizados.');
        }

        $order->delete();

        return back()->with('success', "Pedido #{$order->id} eliminado exitosamente.");
    }
}
