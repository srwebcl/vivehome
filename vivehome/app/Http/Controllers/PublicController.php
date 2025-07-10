<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property; // Importamos el modelo para futuras búsquedas

class PublicController extends Controller
{
    /**
     * Muestra la página de inicio del sitio web público.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Por ahora, solo retornamos la vista.
        // En el futuro, aquí cargaremos propiedades destacadas, etc.
        return view('public.home');
    }
}