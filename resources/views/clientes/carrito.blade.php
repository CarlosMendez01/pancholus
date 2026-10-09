@extends('clientes.index')

@section('content')
<div class="max-w-2xl mx-auto my-8 p-6 md:p-8 bg-white rounded-3xl border-2 border-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] font-sans">
    
    <!-- Encabezado -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2">
            <!-- Icono de Bolsa -->
            <span class="text-2xl">🛍️</span>
            <h1 class="text-xl md:text-2xl font-black text-black tracking-wide uppercase">TU PEDIDO</h1>
        </div>
        <!-- Badge Ítems -->
        <span class="bg-[#F3A538] text-black text-xs md:text-sm font-black px-3.5 py-1 rounded-full border border-black shadow-[1px_1px_0px_0px_rgba(0,0,0,1)]">
            {{ count(session('order_loader', [])) }} ítems
        </span>
    </div>
    
    <!-- Lista de Productos -->
    <div class="flex flex-col gap-3.5 mb-6">
        <!-- Ítem 1 -->

        @foreach (session('order_loader') as $comida)
            <div class="flex items-center justify-between p-4 bg-[#F5F1E8] rounded-2xl border border-black/80 shadow-[3px_3px_0px_0px_rgba(0,0,0,0.9)]">
                <div class="flex items-center gap-2 font-bold text-base text-gray-900">
                    <span class="text-[#C4381C] font-black">{{ $comida['quantity'] ?? 1 }}x</span>
                    <span>{{ $comida['name'] ?? 'Producto' }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="font-black text-gray-900 text-base">${{ $comida['price'] ?? 0 }}</span>
                    <button type="button" class="text-gray-500 hover:text-black font-bold text-lg leading-none ml-1">✕</button>
                </div>
            </div>
        @endforeach

    </div>

    <!-- Bloque Total -->
    <div class="flex items-center justify-between p-4 md:p-5 bg-[#F5F1E8] rounded-2xl border border-black/80 shadow-[3px_3px_0px_0px_rgba(0,0,0,0.9)] mb-5">
        <h2 class="text-lg md:text-xl font-black text-gray-900 uppercase tracking-tight">TOTAL A PAGAR</h2>
        <span class="text-2xl md:text-3xl font-black text-[#B22B0F] tracking-tight">$xxx</span>
    </div>

    <!-- Botón de Confirmación -->
    <button type="button" class="w-full py-4 px-4 bg-[#B22B0F] hover:bg-[#96230b] text-white font-black text-base md:text-lg uppercase tracking-wider rounded-2xl border border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all active:translate-y-0.5 active:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] mb-5">
        Confirmar y Pagar ($xxx)
    </button>

    <!-- Caja Informativa / Notificación -->
    <div class="flex items-center gap-3 p-4 bg-[#ECE7DC] rounded-2xl border border-black/70 shadow-[3px_3px_0px_0px_rgba(0,0,0,0.85)]">
        <span class="text-2xl shrink-0">🍳</span>
        <p class="text-xs md:text-sm font-bold text-gray-800 leading-snug">
            <span class="font-black">Tiempo de preparación:</span> 15–20 min. Notificación por SMS / WhatsApp en vivo.
        </p>
    </div>

</div>
@endsection