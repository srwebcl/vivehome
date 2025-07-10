<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAsesorRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Verifica si el usuario ha iniciado sesión Y si su columna 'role' es 'asesor'.
        if (Auth::check() && Auth::user()->role === 'asesor') {
            // 2. Si cumple ambas condiciones, permite que la petición continúe.
            return $next($request);
        }

        // 3. Si no es un asesor, detiene la petición y muestra la página de error 403.
        abort(403, 'Acceso no autorizado. Se requiere rol de Asesor.');
    }
}