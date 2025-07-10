<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Muestra la vista de login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Maneja una petición de autenticación.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // --- INICIO DE MODIFICACIÓN ---
        // Se añade lógica para redirigir al usuario según su rol.
        $user = $request->user();

        if ($user->role === 'admin') {
            // Si el rol es 'admin', se redirige al dashboard de administración.
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        if ($user->role === 'asesor') {
            // Si el rol es 'asesor', se redirige a su listado de propiedades.
            return redirect()->intended(route('asesor.properties.index', absolute: false));
        }

        // Si no tiene un rol definido, se usa la ruta por defecto 'dashboard'.
        return redirect()->intended(route('dashboard', absolute: false));
        // --- FIN DE MODIFICACIÓN ---
    }

    /**
     * Destruye una sesión autenticada.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}