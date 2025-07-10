<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importar Auth
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado Y si su rol es 'admin'
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request); // Continuar con la solicitud si es admin
        }

        // Si no es admin, redirigir a la página de inicio o mostrar un error 403 (Prohibido)
        // Opción 1: Redirigir al home
        // return redirect('/');

        // Opción 2: Abortar con error 403 (más apropiado para API o cuando no hay un "home" claro para no-admins)
        abort(403, 'Acceso no autorizado.');
    }
}