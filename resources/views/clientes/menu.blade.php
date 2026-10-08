@extends('clientes.index')
@section('content')
    <div>
            <div class="flex items-center gap-3 overflow-x-auto py-4 no-scrollbar">
    
                <!-- TODOS LOS PRODUCTOS -->
                <a href="{{ route('cliente.menu') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#E8E4DA] text-gray-800 text-xs font-bold tracking-wider uppercase shadow-[2px_2px_0px_0px_rgba(0,0,0,0.85)] border border-gray-400/40 shrink-0 transition-all outline-none hover:bg-[#dfdad0] focus:bg-[#C4381C] focus:text-white focus:font-black focus:border-black/10 active:translate-y-0.5">
                    <span>TODOS LOS PRODUCTOS</span>
                </a>

                <!-- PANCHOS ESPECIALES -->
                <a href="{{ route('cliente.menu', 'especial') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#E8E4DA] text-gray-800 text-xs font-bold tracking-wider uppercase shadow-[2px_2px_0px_0px_rgba(0,0,0,0.85)] border border-gray-400/40 shrink-0 transition-all outline-none hover:bg-[#dfdad0] focus:bg-[#C4381C] focus:text-white focus:font-black focus:border-black/10 active:translate-y-0.5">
                    <span>🌭</span>
                    <span>PANCHOS ESPECIALES</span>
                </a>

                <!-- SMASH BURGERS -->
                <a href="#" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#E8E4DA] text-gray-800 text-xs font-bold tracking-wider uppercase shadow-[2px_2px_0px_0px_rgba(0,0,0,0.85)] border border-gray-400/40 shrink-0 transition-all outline-none hover:bg-[#dfdad0] focus:bg-[#C4381C] focus:text-white focus:font-black focus:border-black/10 active:translate-y-0.5">
                    <span>🍔</span>
                    <span>SMASH BURGERS</span>
                </a>

                <!-- PAPAS RÚSTICAS -->
                <a href="#" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#E8E4DA] text-gray-800 text-xs font-bold tracking-wider uppercase shadow-[2px_2px_0px_0px_rgba(0,0,0,0.85)] border border-gray-400/40 shrink-0 transition-all outline-none hover:bg-[#dfdad0] focus:bg-[#C4381C] focus:text-white focus:font-black focus:border-black/10 active:translate-y-0.5">
                    <span>🍟</span>
                    <span>PAPAS RÚSTICAS</span>
                </a>

                <!-- CERVEZAS & BEBIDAS -->
                <a href="#" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#E8E4DA] text-gray-800 text-xs font-bold tracking-wider uppercase shadow-[2px_2px_0px_0px_rgba(0,0,0,0.85)] border border-gray-400/40 shrink-0 transition-all outline-none hover:bg-[#dfdad0] focus:bg-[#C4381C] focus:text-white focus:font-black focus:border-black/10 active:translate-y-0.5">
                    <span>🍺</span>
                    <span>CERVEZAS & BEBIDAS</span>
                </a>

                <!-- POSTRES & SALSAS -->
                <a href="#" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#E8E4DA] text-gray-800 text-xs font-bold tracking-wider uppercase shadow-[2px_2px_0px_0px_rgba(0,0,0,0.85)] border border-gray-400/40 shrink-0 transition-all outline-none hover:bg-[#dfdad0] focus:bg-[#C4381C] focus:text-white focus:font-black focus:border-black/10 active:translate-y-0.5">
                    <span>🥫</span>
                    <span>POSTRES & SALSAS</span>
                </a>

            </div>

            <div class="my-6">
                <!-- Encabezado flex para alinear el título a la izquierda y el contador a la derecha -->
                <div class="flex items-end justify-between mb-6">
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

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($comidas as $comida)
                    <!-- Tarjeta del Producto -->
                    <div class="bg-white rounded-2xl border-2 border-black/80 shadow-[4px_4px_0px_0px_rgba(0,0,0,0.9)] overflow-hidden flex flex-col justify-between p-0 transition-transform">
                        
                        <!-- Contenedor Superior (Imagen) -->
                        <div class="w-full h-52 bg-gray-200 overflow-hidden relative border-b-2 border-black/10">
                            <img src="{{ asset('imagenes/superpancho.png') }}" 
                            alt="{{ $comida->name }}" 
                            class="w-full h-full object-cover">
                        </div>

                        <!-- Cuerpo de la Tarjeta -->
                        <div class="p-5 flex flex-col justify-between flex-grow gap-4">
                            
                            <!-- Nombre del Producto -->
                            <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight leading-tight">
                                {{ $comida->name }}
                            </h3>

                            <p class="text-sm font-medium text-gray-600 leading-snug line-clamp-3">
                                {{ $comida->details }}
                            </p>

                            <!-- Bloque Inferior: Precio y Acciones -->
                            <div class="flex flex-col gap-3 mt-auto">
                                <!-- Precio -->
                                <p class="text-2xl font-black text-[#C4381C] tracking-tight">
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
                                        class="w-16 h-11 text-center font-black text-gray-800 bg-[#EFECE6] border border-gray-400 rounded-xl shadow-[2px_2px_0px_0px_rgba(0,0,0,0.85)] focus:outline-none focus:ring-2 focus:ring-amber-400"
                                    >

                                    <!-- Botón Agregar -->
                                    <button type="button" class="flex-1 h-11 bg-[#F5A631] hover:bg-[#e09525] text-gray-900 font-black text-xs uppercase tracking-wider rounded-xl border border-black/20 shadow-[2px_2px_0px_0px_rgba(0,0,0,0.85)] flex items-center justify-center gap-1 transition-all active:translate-y-0.5">
                                        <span>+</span>
                                        <span>AGREGAR</span>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
@endsection