<!-- Filtro por estado -->
@php
    $pending = App\Models\Order::STATUS_PENDIENTE;
    $realized = App\Models\Order::STATUS_REALIZADO;
    $cancelled = App\Models\Order::STATUS_CANCELADO;

    $filters = [
        ['key' => $pending, 'label' => 'Pendientes', 'count' => $counts[$pending] ?? 0, 'active' => 'bg-amber-500 text-white', 'idle' => 'text-amber-700 bg-amber-50 hover:bg-amber-100'],
        ['key' => $realized, 'label' => 'Realizados', 'count' => $counts[$realized] ?? 0, 'active' => 'bg-green-600 text-white', 'idle' => 'text-green-700 bg-green-50 hover:bg-green-100'],
        ['key' => $cancelled, 'label' => 'Cancelados', 'count' => $counts[$cancelled] ?? 0, 'active' => 'bg-red-600 text-white', 'idle' => 'text-red-700 bg-red-50 hover:bg-red-100'],
        ['key' => 'todos', 'label' => 'Todos', 'count' => ($counts[$pending] ?? 0) + ($counts[$realized] ?? 0) + ($counts[$cancelled] ?? 0), 'active' => 'bg-gray-800 text-white', 'idle' => 'text-gray-700 bg-gray-50 hover:bg-gray-100'],
    ];
@endphp

<div class="mb-5">
    <div class="inline-flex flex-wrap gap-2">
        @foreach($filters as $filter)
            <a
                href="{{ route('empleado.pedidos.index', ['estado' => $filter['key']]) }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-colors
                    {{ $status === $filter['key'] ? $filter['active'] : $filter['idle'] }}"
            >
                {{ $filter['label'] }}
                <span class="inline-flex items-center justify-center min-w-5 h-5 px-1.5 rounded-full text-xs font-bold
                    {{ $status === $filter['key'] ? 'bg-white/25' : 'bg-white text-current' }}">
                    {{ $filter['count'] }}
                </span>
            </a>
        @endforeach
    </div>
</div>

<!-- Lista de pedidos -->
<div class="space-y-3">
    <div class="hidden md:grid md:grid-cols-6 gap-4 px-4 py-2 bg-gray-50 rounded-lg text-xs font-semibold text-gray-500 uppercase tracking-wider">
        <div>Pedido</div>
        <div>Cliente</div>
        <div>Items</div>
        <div>Total</div>
        <div>Fecha</div>
        <div class="text-right">Acciones</div>
    </div>

    @forelse($orders as $order)
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-center p-4 bg-white border border-gray-200 rounded-lg hover:shadow-sm transition-shadow">
            <!-- Pedido -->
            <div class="font-medium text-gray-800">
                <span class="md:hidden font-semibold text-gray-500 block text-xs uppercase">Pedido:</span>
                #{{ $order->id }}
            </div>

            <!-- Cliente -->
            <div class="text-gray-700">
                <span class="md:hidden font-semibold text-gray-500 block text-xs uppercase">Cliente:</span>
                <p class="capitalize">{{ $order->user->name }} {{ $order->user->last_name }}</p>
                <p class="text-xs text-gray-500">{{ $order->user->email }}</p>
            </div>

            <!-- Items -->
            <div class="text-gray-600">
                <span class="md:hidden font-semibold text-gray-500 block text-xs uppercase">Items:</span>
                <ul class="text-sm">
                    @foreach($order->details as $detail)
                        <li class="capitalize">{{ $detail->meal->name }} x{{ $detail->quantity }}</li>
                    @endforeach
                </ul>
            </div>

            <!-- Total -->
            <div class="font-semibold text-gray-800">
                <span class="md:hidden font-semibold text-gray-500 block text-xs uppercase">Total:</span>
                ${{ number_format($order->details->sum(fn ($detail) => $detail->quantity * $detail->meal->price), 2, ',', '.') }}
            </div>

            <!-- Fecha -->
            <div class="text-gray-500 text-sm">
                <span class="md:hidden font-semibold text-gray-500 block text-xs uppercase">Fecha:</span>
                {{ $order->created_at->format('d/m/Y H:i') }}
            </div>

            <!-- Acciones -->
            <div class="md:text-right pt-2 md:pt-0 border-t md:border-t-0 border-gray-100 flex md:justify-end gap-2">
                @if($order->status === 'pendiente')
                    <form action="{{ route('empleado.pedidos.realizar', $order) }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="status" value="{{ \App\Models\Order::STATUS_REALIZADO }}">
                        <button
                            type="submit"
                            onclick="return confirm('¿Marcar el pedido #{{ $order->id }} como realizado?')"
                            class="px-3 py-1.5 text-xs font-medium text-green-700 bg-green-100 rounded-lg hover:bg-green-200 transition-colors"
                        >
                            Realizado
                        </button>
                    </form>

                    <form action="{{ route('empleado.pedidos.cancelar', $order) }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="status" value="{{ \App\Models\Order::STATUS_CANCELADO }}">
                        <button
                            type="submit"
                            onclick="return confirm('¿Cancelar el pedido #{{ $order->id }}?')"
                            class="px-3 py-1.5 text-xs font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200 transition-colors"
                        >
                            Cancelado
                        </button>
                    </form>
                @else
                    <span class="px-3 py-1.5 text-xs font-semibold rounded-full capitalize
                        {{ $order->status === 'realizado' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $order->status }}
                    </span>
                @endif
            </div>
        </div>
    @empty
        <div class="p-8 text-center bg-gray-50 rounded-lg border border-dashed border-gray-300">
            <p class="text-gray-500 text-sm">No hay pedidos {{ $status === 'todos' ? '' : $status }}.</p>
        </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $orders->links() }}
</div>
