@extends('clientes.index')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
    
    <!-- Columna Izquierda: Hero Text -->
    <div class="lg:col-span-7 flex flex-col items-start gap-4">
        
        <!-- Badges de cabecera -->
        <div class="flex flex-wrap items-center gap-3 text-xs font-black uppercase tracking-wider">
            <!-- Badge Naranja -->
            <span class="flex items-center gap-1.5 bg-[#F5A631] text-gray-900 px-3.5 py-1.5 rounded-full border border-black/20 shadow-[2px_2px_0px_0px_rgba(0,0,0,0.85)]">
                <span>🔥</span> FUEGO LENTO & AHUMADO REAL
            </span>
            <!-- Badge Crema -->
            <span class="flex items-center gap-1.5 bg-[#EFECE6] text-gray-800 px-3.5 py-1.5 rounded-full border border-gray-300 shadow-[2px_2px_0px_0px_rgba(0,0,0,0.85)]">
                <span>⏱️</span> DESPACHO EN 15-20 MIN
            </span>
        </div>

        <!-- Título Principal -->
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-[#C4381C] uppercase leading-[0.95] tracking-tight mt-2">
            ¡El auténtico sabor sobre ruedas!
        </h1>

        <!-- Descripción -->
        <p class="text-gray-700 font-medium text-base md:text-lg leading-relaxed max-w-2xl mt-1">
            Panchos artesanales gigantes con salchichas de autor, smash burgers a la plancha caliente y papas rústicas cortadas al día, servidas con amor callejero.
        </p>
    </div>

    <!-- Columna Derecha: Tarjeta de la Sede -->
    <div class="lg:col-span-5 relative mt-4 lg:mt-0">
        
        <!-- Badge Flotante Superior -->
        <div class="absolute -top-3.5 right-4 z-10 bg-[#F5A631] text-gray-900 text-xs font-black uppercase tracking-wider px-3.5 py-1 rounded-full border border-black/20 shadow-[2px_2px_0px_0px_rgba(0,0,0,0.85)] flex items-center gap-1">
            <span>📍⭐</span> NUESTRA SEDE CENTRAL
        </div>

        <!-- Tarjeta Roja Principal -->
        <div class="bg-[#C4381C] text-white p-6 md:p-8 rounded-2xl shadow-[6px_6px_0px_0px_rgba(0,0,0,0.85)] border-2 border-black/20 relative overflow-hidden">
            
            <!-- Encabezado con Logo y Nombre -->
            <div class="flex items-center gap-4 mb-6">
                <img src="{{ asset('imagenes/pancholus_logo.png') }}" 
                     alt="Logo Pancholus" 
                     class="w-16 h-16 rounded-full border-2 border-white/30 object-cover shadow-sm shrink-0">
                <div>
                    <span class="text-[10px] font-bold text-amber-200 tracking-widest uppercase block">
                        PANCHOLUS FOODTRUCK
                    </span>
                    <h2 class="text-xl md:text-2xl font-black leading-tight uppercase">
                        Sede Central & Patio Gastronómico
                    </h2>
                </div>
            </div>

            <!-- Recuadro Blanco: Dirección Exacta -->
            <div class="bg-white text-gray-900 p-4 rounded-xl shadow-sm border border-black/10 flex items-start gap-3 mb-4">
                <span class="text-xl leading-none text-[#C4381C] mt-0.5">📍</span>
                <div>
                    <h3 class="text-xs font-black uppercase tracking-wide text-gray-900">
                        DIRECCIÓN EXACTA
                    </h3>
                    <p class="text-xs font-medium text-gray-600 leading-snug mt-0.5">
                        Avenida Junín, casi Santa Fe (Plaza Central of Sabores, Puesto #4)
                    </p>
                </div>
            </div>

            <!-- Recuadro Translúcido: Puntos de referencia -->
            <div class="bg-black/15 p-4 rounded-xl border border-white/10 text-xs font-semibold text-amber-100 flex flex-col gap-2 mb-6">
                <div class="flex items-center gap-2">
                    <span class="text-white">🎯</span>
                    <span>A 100m de la Fuente Central</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-white">🅿️</span>
                    <span>Estacionamiento disponible sobre calle Junín</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-white">🪑</span>
                    <span>Mesas al aire libre y retiro express por ventanilla</span>
                </div>
            </div>

            <!-- Pie de tarjeta: Horario y Botón -->
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <!-- Horario -->
                <div class="w-full sm:w-auto flex-1 flex items-center justify-center gap-2 bg-[#EFECE6] text-gray-900 px-4 py-2.5 rounded-xl border border-black/20 text-xs font-black shadow-[2px_2px_0px_0px_rgba(0,0,0,0.85)]">
                    <span>🕒</span>
                    <span>Mar a Dom • 18:30 a 01:00 hs</span>
                </div>

                <!-- Botón Google Maps -->
                <a href="https://maps.google.com" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[#F5A631] hover:bg-[#e09525] text-gray-900 px-4 py-2.5 rounded-xl border border-black/20 text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_rgba(0,0,0,0.85)] transition-transform active:translate-y-0.5">
                    <span>🗺️</span>
                    <span>ABRIR EN GOOGLE MAPS</span>
                </a>
            </div>

        </div>
    </div>

</div>
@endsection