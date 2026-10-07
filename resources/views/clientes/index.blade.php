<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#F8F5EE] font-sans antialiased text-gray-800">

        <!-- Barra de Navegación -->
        <header class="w-full bg-[#F8F5EE] border-b border-gray-200/60 px-6 py-4">
            <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
                
                <!-- Logo + Nombre -->
                <div class="flex items-center gap-3 shrink-0">
                    <img src="{{ asset('imagenes/pancholus_logo.png') }}" 
                        alt="Logo de pancholus" 
                        class="w-11 h-11 rounded-full object-cover shadow-sm border border-gray-300">
                    
                    <div class="flex flex-col justify-center leading-tight">
                        <span class="font-black text-[#C4381C] text-xl tracking-tight uppercase">
                            Pancholus
                        </span>
                        <span class="text-[9px] font-bold text-gray-500 tracking-wider uppercase">
                            Foodtruck de sabores
                        </span>
                    </div>
                </div>

                <!-- Menú de Navegación -->
                <nav class="flex items-center gap-2 text-xs font-extrabold tracking-wider uppercase text-gray-700">
                    
                    <!-- INICIO -->
                    <a href="#" class="hover:text-[#C4381C] px-4 py-2 rounded-xl transition-all focus:bg-[#C4381C] focus:text-white focus:shadow-[3px_3px_0px_0px_rgba(0,0,0,0.8)] focus:bg-[#C4381C] focus:text-white focus:shadow-[3px_3px_0px_0px_rgba(0,0,0,0.8)] active:translate-y-0.5 outline-none">
                        INICIO
                    </a>
                    
                    <!-- MENÚ -->
                    <a href="#" class="hover:text-[#C4381C] px-4 py-2 rounded-xl transition-all focus:bg-[#C4381C] focus:text-white focus:shadow-[3px_3px_0px_0px_rgba(0,0,0,0.8)] focus:bg-[#C4381C] focus:text-white focus:shadow-[3px_3px_0px_0px_rgba(0,0,0,0.8)] active:translate-y-0.5 outline-none">
                        MENÚ
                    </a>
                    
                    <!-- PROMOCIONES -->
                    <a href="#" class="hover:text-[#C4381C] px-4 py-2 rounded-xl transition-all focus:bg-[#C4381C] focus:text-white focus:shadow-[3px_3px_0px_0px_rgba(0,0,0,0.8)] focus:bg-[#C4381C] focus:text-white focus:shadow-[3px_3px_0px_0px_rgba(0,0,0,0.8)] active:translate-y-0.5 outline-none">
                        PROMOCIONES
                    </a>
                    
                    <!-- SOBRE NOSOTROS -->
                    <a href="#" class="hover:text-[#C4381C] px-4 py-2 rounded-xl transition-all focus:bg-[#C4381C] focus:text-white focus:shadow-[3px_3px_0px_0px_rgba(0,0,0,0.8)] focus:bg-[#C4381C] focus:text-white focus:shadow-[3px_3px_0px_0px_rgba(0,0,0,0.8)] active:translate-y-0.5 outline-none">
                        SOBRE NOSOTROS
                    </a>
                    
                    <!-- UBICACIÓN -->
                    <a href="#" class="hover:text-[#C4381C] px-4 py-2 rounded-xl transition-all focus:bg-[#C4381C] focus:text-white focus:shadow-[3px_3px_0px_0px_rgba(0,0,0,0.8)] focus:bg-[#C4381C] focus:text-white focus:shadow-[3px_3px_0px_0px_rgba(0,0,0,0.8)] active:translate-y-0.5 outline-none">
                        UBICACIÓN
                    </a>
                    
                    <!-- Botón Carrito -->
                    <a href="#" class="flex items-center gap-2 bg-[#F5A631] text-gray-900 px-4 py-2 rounded-xl border border-amber-600/40 shadow-[3px_3px_0px_0px_rgba(0,0,0,0.8)] hover:translate-y-0.5 transition-all relative ml-2 outline-none">
                        <div class="relative">
                            <svg class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <div class="flex flex-col text-left leading-none">
                            <span class="text-[9px] font-bold opacity-80">CARRITO</span>
                        </div>
                    </a>

                </nav>

            </div>
        </header>

        <!-- Contenido principal -->
        <main class="max-w-7xl mx-auto py-6 px-4">
            @yield('content')
        </main>

    </body>
</html>