<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Empleado - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Barra superior -->
    <header class="bg-white border-b border-gray-200">
        <div class="flex items-center justify-between px-6 py-3">

            <div class="flex items-center gap-3">
                <img src="{{ asset('imagenes/pancholus_logo.png') }}"
                    alt="Logo de pancholus"
                    class="w-9 h-9 rounded-full object-cover shadow-sm border border-gray-300">
                <div class="leading-tight">
                    <span class="font-bold text-[#C4381C] tracking-tight uppercase text-sm">Pancholus</span>
                    <span class="hidden sm:block text-[10px] font-bold text-gray-500 tracking-wider uppercase">Panel Empleado</span>
                </div>
            </div>

            <!-- Sesión del empleado (esquina superior derecha) -->
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2.5 bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5">
                    <div class="w-9 h-9 rounded-full bg-blue-900 text-white flex items-center justify-center font-bold uppercase text-sm">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="leading-tight hidden sm:block">
                        <p class="text-sm font-semibold text-gray-800 capitalize">
                            {{ auth()->user()->name }} {{ auth()->user()->last_name }}
                        </p>
                        <p class="text-xs text-gray-500 capitalize">
                            {{ auth()->user()->role->role ?? 'Empleado' }}
                        </p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="text-xs font-semibold text-red-600 hover:text-red-800 border border-red-200 bg-white hover:bg-red-50 px-2.5 py-1.5 rounded-lg transition-colors">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </header>

    <!-- Contenedor principal que alinea Sidebar y Contenido lado a lado -->
    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-64 bg-blue-900 text-white flex flex-col flex-shrink-0">
            @section('sidebar')
                <!-- Cabecera / Logo -->
                <!--<div class="bg-blue-950 p-4 text-center text-lg font-bold border-b border-blue-800">
                    SGP
                </div>-->

                <!-- Menú de navegación -->
                <nav class="p-4 space-y-2 text-left flex-1">
                    <a href="{{ route('empleado.inicio') }}"
                        class="block px-4 py-2 rounded transition {{ request()->routeIs('empleado.inicio') ? 'bg-blue-800 text-white font-semibold' : 'hover:bg-blue-800 text-white' }}">
                        Inicio
                    </a>

                    <a href="{{ route('empleado.pedidos.index') }}"
                        class="block px-4 py-2 rounded transition {{ request()->routeIs('empleado.pedidos.*') ? 'bg-blue-800 text-white font-semibold' : 'hover:bg-blue-800 text-white' }}">
                        Pedidos
                    </a>
                </nav>
            @show
        </aside>

        <!-- Área de contenido dinámico -->
        <main class="flex-1 p-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                @yield('content')
            </div>
        </main>

    </div>

</body>
</html>
