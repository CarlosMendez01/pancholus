<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta - Pancholus</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F3EFE6] min-h-screen flex items-center justify-center p-4 font-sans text-gray-800">

    <div class="w-full max-w-md bg-[#F3EFE6] p-6 sm:p-8 rounded-3xl">
        
        <!-- Pestañas Superiores -->
        <div class="flex bg-[#EBE5D8] p-1.5 rounded-2xl border-2 border-black mb-8 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
            <a href="{{ route('login') }}" class="w-1/2 py-2.5 text-center text-gray-700 font-bold text-sm uppercase rounded-xl hover:text-black transition-colors self-center tracking-wider">
                Iniciar Sesión
            </a>
            <button class="w-1/2 py-2.5 bg-[#C83B1D] text-white font-black text-sm uppercase rounded-xl border-2 border-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] tracking-wider">
                Crear Cuenta
            </button>
        </div>

        @if(session('status'))
            <div class="mb-4 p-4 text-sm text-green-900 bg-green-100 rounded-xl border-2 border-black font-semibold">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Nombre y Apellido -->
            <div>
                <label for="name" class="block text-xs font-black text-black uppercase tracking-wider mb-1.5">
                    Nombre y Apellido
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name') }}"
                        placeholder="Ej. Carlos Valenzuela"
                        required
                        autofocus
                        class="w-full pl-11 pr-4 py-2.5 bg-[#FAF6EE] border-2 border-black rounded-2xl focus:outline-none focus:ring-2 focus:ring-black text-gray-800 placeholder-gray-400 font-medium text-sm shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]"
                    >
                </div>
                @error('name')
                    <p class="mt-1 text-xs font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- WhatsApp / Celular -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="phone" class="block text-xs font-black text-black uppercase tracking-wider">
                        WhatsApp / Celular
                    </label>
                    <span class="text-[10px] font-black text-[#8C6D14] uppercase tracking-wider">Para avisar pedido listo</span>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <input
                        type="text"
                        name="phone"
                        id="phone"
                        value="{{ old('phone') }}"
                        placeholder="+56 9 1234 5678"
                        required
                        class="w-full pl-11 pr-4 py-2.5 bg-[#FAF6EE] border-2 border-black rounded-2xl focus:outline-none focus:ring-2 focus:ring-black text-gray-800 placeholder-gray-400 font-medium text-sm shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]"
                    >
                </div>
                @error('phone')
                    <p class="mt-1 text-xs font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Correo Electrónico -->
            <div>
                <label for="email" class="block text-xs font-black text-black uppercase tracking-wider mb-1.5">
                    Correo Electrónico
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        placeholder="tu.correo@ejemplo.com"
                        required
                        class="w-full pl-11 pr-4 py-2.5 bg-[#FAF6EE] border-2 border-black rounded-2xl focus:outline-none focus:ring-2 focus:ring-black text-gray-800 placeholder-gray-400 font-medium text-sm shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]"
                    >
                </div>
                @error('email')
                    <p class="mt-1 text-xs font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Contraseñas (Grid de 2 columnas) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <!-- Contraseña -->
                <div>
                    <label for="password" class="block text-xs font-black text-black uppercase tracking-wider mb-1.5">
                        Contraseña
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            placeholder="Mínimo 8 caracteres"
                            required
                            class="w-full pl-9 pr-3 py-2.5 bg-[#FAF6EE] border-2 border-black rounded-2xl focus:outline-none focus:ring-2 focus:ring-black text-gray-800 placeholder-gray-400 font-medium text-xs shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]"
                        >
                    </div>
                </div>

                <!-- Confirmar Contraseña -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-black text-black uppercase tracking-wider mb-1.5">
                        Confirmar
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            placeholder="Repite contraseña"
                            required
                            class="w-full pl-9 pr-3 py-2.5 bg-[#FAF6EE] border-2 border-black rounded-2xl focus:outline-none focus:ring-2 focus:ring-black text-gray-800 placeholder-gray-400 font-medium text-xs shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]"
                        >
                    </div>
                </div>
            </div>
            @error('password')
                <p class="mt-1 text-xs font-bold text-red-600">{{ $message }}</p>
            @enderror

            <!-- Términos y Condiciones -->
            <div class="flex items-center pt-1">
                <input
                    id="terms"
                    type="checkbox"
                    name="terms"
                    required
                    class="w-4 h-4 text-[#F5A623] bg-[#FAF6EE] border-2 border-black rounded focus:ring-0 cursor-pointer"
                >
                <label for="terms" class="ml-2.5 text-xs font-medium text-gray-700">
                    Acepto los <a href="#" class="text-[#C83B1D] font-bold underline">TÉRMINOS DE SERVICIO</a> y aviso de privacidad.
                </label>
            </div>

            <!-- Botón de Registro -->
            <button
                type="submit"
                class="w-full bg-[#F5A623] hover:bg-[#e0951a] text-black font-extrabold text-base uppercase py-3.5 px-4 rounded-2xl border-2 border-black shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] flex items-center justify-center gap-2 transition-transform active:translate-y-0.5 mt-2"
            >
                <!-- Icono de Fiesta / Confeti -->
                <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                REGISTRARME Y EMPEZAR A PEDIR
            </button>
        </form>

        <!-- Enlace a Iniciar Sesión -->
        <p class="mt-6 text-center text-xs font-bold text-gray-600">
            ¿Ya estás registrado? 
            <a href="{{ route('login') }}" class="text-[#C83B1D] underline uppercase font-black tracking-wider ml-1 hover:text-black">
                Inicia sesión aquí
            </a>
        </p>

    </div>

</body>
</html>