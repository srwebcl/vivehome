<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Aquí es donde registras los alias de middleware
        $middleware->alias([
            'admin' => \App\Http\Middleware\CheckAdminRole::class,
            'asesor' => \App\Http\Middleware\CheckAsesorRole::class, // <-- ESTA ES LA LÍNEA QUE SOLUCIONA EL ERROR
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();