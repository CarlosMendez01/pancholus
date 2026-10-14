@extends('clientes.index')
@section('content')
    <div>
        @if (session('success'))
            <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
                <ul class="list-disc list-inside text-sm font-semibold">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
            <div class="flex items-center justify-center gap-3 overflow-x-auto py-4 no-scrollbar">

                @foreach($all_filters as $filter)
                    <a href="{{ route('cliente.menu', $filter->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#E8E4DA] text-gray-800 text-xs font-bold tracking-wider uppercase shadow-[2px_2px_0px_0px_rgba(0,0,0,0.85)] border border-gray-400/40 shrink-0 transition-all outline-none hover:bg-[#dfdad0] focus:bg-[#C4381C] focus:text-white focus:font-black focus:border-black/10 active:translate-y-0">
                    @if ($filter->name === null)
                        <span>TODOS LOS PRODUCTOS</span>
                    @else
                        <span>{{ $filter->name }}</span>
                    @endif
                    </a>
                @endforeach
            </div>

            <div class="my-6">
                <!-- Encabezado flex para alinear el título a la izquierda y el contador a la derecha -->
                <div class="max-w-6xl mx-auto mb-6 px-2 sm:px-0">
                    <div class="flex items-end justify-between">
                        <div>
                            <!-- Subtítulo pequeño superpuesto -->
                            <p class="text-xs font-black text-[#A36128] tracking-widest uppercase mb-1">
                                NUESTRA CARTA CALLEJERA
                            </p>
                            
                            <!-- Título principal grande -->
                            <h2 class="text-3xl md:text-4xl font-black text-[#1A1A1A] tracking-tight uppercase leading-none">
                                PLATOS & ESPECIALIDADES
                            </h2>
                        </div>
                    </div>
                </div>

                <div class="max-w-6xl mx-auto">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 justify-center">
                        @foreach ($comidas as $comida)
                        <!-- Tarjeta del Producto -->
                        <form action="{{ route('cliente.carrito.agregar') }}" method="POST">
                            @csrf
                            <input type="hidden" name="meal_id" value="{{ $comida->id }}">

                            <div class="bg-white rounded-2xl border-2 border-black/80 shadow-[4px_4px_0px_0px_rgba(0,0,0,0.9)] overflow-hidden flex flex-col justify-between p-0 transition-transform w-full">
                            
                                <!-- Contenedor Superior (Imagen más compacta) -->
                                <div class="w-full h-40 bg-gray-200 overflow-hidden relative border-b-2 border-black/10">
                                    <img src="{{ asset('imagenes/superpancho.png') }}" 
                                    alt="{{ $comida->name }}" 
                                    class="w-full h-full object-cover">
                                </div>

                                <!-- Cuerpo de la Tarjeta -->
                                <div class="p-4 flex flex-col justify-between flex-grow gap-3">
                                    
                                    <!-- Nombre del Producto -->
                                    <h3 class="text-lg font-black text-gray-900 uppercase tracking-tight leading-tight">
                                        {{ $comida->name }}
                                    </h3>

                                    <p class="text-xs font-medium text-gray-600 leading-snug line-clamp-3">
                                        {{ $comida->details }}
                                    </p>

                                    <!-- Bloque Inferior: Precio y Acciones -->
                                    <div class="flex flex-col gap-2 mt-auto pt-2">
                                        <!-- Precio -->
                                        <p class="text-xl font-black text-[#C4381C] tracking-tight">
                                            ${{ number_format($comida->price, 0, ',', '.') }}
                                        </p>

                                        <!-- Input de Cantidad + Botón Agregar -->
                                        <div class="flex items-center gap-2">
                                            <!-- Selector de Cantidad -->
                                            <input 
                                                type="number" 
                                                name="quantity" 
                                                value="1" 
                                                min="1" 
                                                required
                                                class="w-14 h-10 text-center font-black text-sm text-gray-800 bg-[#EFECE6] border border-gray-400 rounded-xl shadow-[2px_2px_0px_0px_rgba(0,0,0,0.85)] focus:outline-none focus:ring-2 focus:ring-amber-400"
                                            >

                                            <!-- Botón Agregar -->
                                            <button type="submit" class="flex-1 h-10 bg-[#F5A631] hover:bg-[#e09525] text-gray-900 font-black text-xs uppercase tracking-wider rounded-xl border border-black/20 shadow-[2px_2px_0px_0px_rgba(0,0,0,0.85)] flex items-center justify-center gap-1 transition-all active:translate-y-0.5">
                                                <span>+</span>
                                                <span>AGREGAR</span>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </form>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
@endsection