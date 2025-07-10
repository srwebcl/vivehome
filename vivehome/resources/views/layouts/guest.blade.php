<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.scss', 'resources/js/app.js'])
    </head>
    <body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
        
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5 col-xl-4">
                    
                    <div class="text-center mb-4">
                        {{-- Puedes poner tu logo aquí. Por ahora, un enlace de texto. --}}
                        <a href="/" class="h3 text-dark text-decoration-none">
                            Vive Home
                        </a>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            {{ $slot }}
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </body>
</html>