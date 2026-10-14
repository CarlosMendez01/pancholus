<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Name - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Contenedor principal que alinea Sidebar y Contenido lado a lado -->
    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-64 bg-blue-900 text-white flex flex-col flex-shrink-0">
            @section('sidebar')
                <!-- Cabecera / Logo SGP -->
                <div class="bg-blue-950 p-4 text-center text-xl font-bold border-b border-blue-800">
                    SGP
                </div>

                <!-- Menú de navegación -->
                <nav class="p-4 space-y-2 text-left">
                    <a href="/admin/" class="block px-4 py-2 rounded hover:bg-blue-800 text-white transition">
                        Inicio
                    </a>

                    <a href="{{ route('admin.empleados.index') }}" class="block px-4 py-2 rounded hover:bg-blue-800 text-white transition">
                        Empleados
                    </a>

                    <a href="/admin/configuracion/" class="block px-4 py-2 rounded hover:bg-blue-800 text-white transition">
                        Configuración
                    </a>

                    <a href=" {{route ('admin.statistics.index') }}" class="block px-4 py-2 rounded hover:bg-blue-800 text-white transition">
                        Estadísticas
                    </a>
                </nav>
            @show
        </aside>

        <!-- Área de contenido dinámico -->
        <main class="flex-1 p-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                @if($errors->any())
                    <div class="mb-4 p-4 text-sm text-red-800 bg-red-100 rounded-lg border border-red-200">
                        <p class="font-semibold mb-1">Revisá los datos ingresados:</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

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

                @yield('content')
            </div>
        </main>

    </div>

</body>
</html>
