<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>App Name - @yield('title')</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        @section('sidebar')
            En construccion...
        @show

        <div class="container mx-auto p-4">
            @yield('content')
        </div>
    </body>
</html>