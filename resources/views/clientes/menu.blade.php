@extends('clientes.index')

@section('content')

@if(session('success'))
    <div class="mb-4 p-4 text-sm text-green-800 bg-green-100 rounded-lg border border-green-200">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-4 text-sm text-red-800 bg-red-100 rounded-lg border border-red-200">
        {{ session('error') }}
    </div>
@endif

<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Columna principal: menú -->
        <div class="lg:col-span-2">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Invitados a ordenar?</h2>

            @forelse($meals as $meal)
                <div class="flex items-center justify-between p-4 mb-3 bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800 capitalize">{{ $meal->name }}</h3>
                        <p class="text-sm text-[#C4381C] font-bold">${{ number_format($meal->price, 2, ',', '.') }}</p>
                    </div>

                    <form action="{{ route('cliente.carrito.agregar') }}" method="POST"
                          class="flex items-center gap-2" id="agregar-{{ $meal->id }}">
                        @csrf
                        <input type="hidden" name="meal_id" value="{{ $meal->id }}">
                        <input
                            type="number"
                            name="quantity"
                            value="1"
                            min="1"
                            max="99"
                            class="w-16 px-2 py-1.5 text-sm border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-[#F5A631]"
                        >
                        <button
                            type="submit"
                            class="px-4 py-1.5 text-sm font-semibold text-gray-900 bg-[#F5A631] rounded-lg hover:bg-amber-500 transition-colors shadow-sm"
                        >
                            Agregar
                        </button>
                    </form>
                </div>
            @empty
                <div class="p-8 text-center bg-white rounded-xl border border-dashed border-gray-300">
                    <p class="text-gray-500 text-sm">No hay comidas en el menú por el momento.</p>
                </div>
            @endforelse
        </div>

        <!-- Columna lateral: carrito -->
        <aside id="carrito" class="lg:sticky lg:top-6 h-fit bg-white rounded-xl shadow-md border border-gray-100 p-5">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Tu carrito</h3>

            @php($cartItems = collect($cart)->map(function ($qty, $mealId) use ($meals) {
                $meal = $meals->firstWhere('id', $mealId);
                return $meal ? ['meal' => $meal, 'quantity' => $qty] : null;
            })->filter()->values())

            @if($cartItems->isEmpty())
                <p class="text-sm text-gray-500">El carrito está vacío. Elegí alguna comida del menú.</p>
            @else
                <ul class="space-y-3 mb-4">
                    @foreach($cartItems as $item)
                        <li class="flex items-center justify-between gap-2 text-sm">
                            <div>
                                <p class="font-medium text-gray-800 capitalize">{{ $item['meal']->name }}</p>
                                <p class="text-gray-500 text-xs">{{ $item['quantity'] }} x ${{ number_format($item['meal']->price, 2, ',', '.') }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-gray-800">
                                    ${{ number_format($item['quantity'] * $item['meal']->price, 2, ',', '.') }}
                                </span>
                                <form action="{{ route('cliente.carrito.quitar') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="meal_id" value="{{ $item['meal']->id }}">
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-semibold">
                                        Quitar
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>

                @php($total = $cartItems->sum(fn ($item) => $item['quantity'] * $item['meal']->price))

                <div class="flex items-center justify-between border-t border-gray-200 pt-3 mb-4">
                    <span class="text-sm font-semibold text-gray-700">Total</span>
                    <span class="text-lg font-bold text-[#C4381C]">${{ number_format($total, 2, ',', '.') }}</span>
                </div>

                <form action="{{ route('cliente.pedidos.store') }}" method="POST">
                    @csrf
                    @foreach($cartItems as $index => $item)
                        <input type="hidden" name="items[{{ $index }}][meal_id]" value="{{ $item['meal']->id }}">
                        <input type="hidden" name="items[{{ $index }}][quantity]" value="{{ $item['quantity'] }}">
                    @endforeach

                    <button
                        type="submit"
                        class="w-full bg-[#C4381C] hover:bg-red-700 text-white font-semibold py-2.5 px-4 rounded-lg shadow transition-colors duration-200"
                    >
                        Confirmar pedido
                    </button>
                </form>
            @endif
        </aside>

    </div>
</div>

@endsection