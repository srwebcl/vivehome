<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vive Home - Asesores Inmobiliarios</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-light">

    <div class="container">
        <header class="d-flex justify-content-end py-3 mb-4">
            <nav>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-outline-primary me-2">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary me-2">Iniciar Sesión</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-secondary">Registrarse</a>
                        @endif
                    @endauth
                @endif
            </nav>
        </header>

        <main>
            <div class="p-5 mb-4 bg-white rounded-3 shadow-sm">
                <div class="container-fluid py-5 text-center">
                    <h1 class="display-4 fw-bold">Vive Home</h1>
                    <p class="fs-4">Plataforma de Asesores Inmobiliarios</p>
                    <hr class="my-4">
                    <p>Bienvenido al portal. Esta es una página de inicio provisional mientras se desarrolla el sitio público (Fase 4 del proyecto).</p>
                </div>
            </div>
        </main>

        <footer class="text-center text-muted mt-5">
            &copy; {{ date('Y') }} Vive Home
        </footer>
    </div>

</body>
</html>