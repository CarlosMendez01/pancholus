@extends('clientes.index')

@section('content')

@if(session('success'))
    <div class="mb-4 p-4 text-sm text-green-800 bg-green-100 rounded-lg border border-green-200">
        {{ session('success') }}
    </div>
@endif

<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Mis pedidos</h2>
        <a href="{{ route('cliente.menu') }}" class="px-4 py-2 text-sm font-semibold text-gray-900 bg-[#F5A631] rounded-lg hover:bg-amber-500 transition-colors shadow-sm">
            + Nuevo pedido
        </a>
    </div>

    @forelse($orders as $order)
        <div class="mb-4 bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-3">
                <div>
                    <p class="font-semibold text-gray-800">Pedido #{{ $order->id }}</p>
                    <p class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>

                @php($badge = match ($order->status) {
                    'pendiente' => 'bg-amber-100 text-amber-700',
                    'realizado' => 'bg-green-100 text-green-700',
                    'cancelado' => 'bg-red-100 text-red-700',
                    default => 'bg-gray-100 text-gray-700',
                })

                <span class="px-3 py-1 text-xs font-semibold rounded-full capitalize {{ $badge }}">
                    {{ $order->status }}
                </span>
            </div>

            <ul class="space-y-1 text-sm text-gray-700">
                @foreach($order->details as $detail)
                    <li class="flex justify-between">
                        <span class="capitalize">{{ $detail->meal->name }}</span>
                        <span>x{{ $detail->quantity }}</span>
                    </li>
                @endforeach
            </ul>

            <p class="mt-3 text-right font-bold text-[#C4381C]">
                Total: ${{ number_format($order->details->sum(fn ($detail) => $detail->quantity * $detail->meal->price), 2, ',', '.') }}
            </p>
        </div>
    @empty
        <div class="p-8 text-center bg-white rounded-xl border border-dashed border-gray-300">
            <p class="text-gray-500 text-sm">Todavía no realizaste ningún pedido.</p>
        </div>
    @endforelse
</div>

@endsection