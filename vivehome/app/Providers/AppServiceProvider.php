<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // AÑADIMOS ESTA LÍNEA PARA CORREGIR LA RUTA PÚBLICA EN CPANEL
        $this->app->usePublicPath(realpath(base_path('../public_html')));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}