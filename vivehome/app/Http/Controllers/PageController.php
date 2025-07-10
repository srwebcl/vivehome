<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Property;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Muestra la página de inicio del sitio web público.
     * --- MÉTODO ACTUALIZADO ---
     * Ahora también carga las propiedades destacadas.
     */
    public function home()
    {
        // 1. Obtenemos los datos para los filtros de búsqueda
        $filterData = [
            'categories' => Category::orderBy('name')->get(),
            'communes' => Property::where('status', 'Disponible')->whereNotNull('commune')->distinct()->orderBy('commune')->pluck('commune'),
        ];
        
        // 2. Obtenemos las 3 propiedades destacadas más recientes
        $featuredProperties = Property::where('status', 'Disponible')
                                        ->where('is_featured', true)
                                        ->with(['category', 'photos'])
                                        ->latest()
                                        ->take(6) 
                                        ->get();
        
        // 3. Pasamos ambas variables a la vista: los datos para filtros y las propiedades destacadas
        return view('public.home', compact('filterData', 'featuredProperties'));
    }

    /**
     * Muestra la página de listado de propiedades y aplica los filtros.
     * (Este método ya estaba correcto y no se ha modificado).
     */
    public function properties(Request $request)
    {
        // 1. Iniciar la consulta de propiedades disponibles con sus relaciones
        $query = Property::where('status', 'Disponible')->with(['category', 'photos']);

        // 2. Aplicar filtros si existen en la petición
        if ($request->filled('operation_type')) {
            $query->where('operation_type', $request->operation_type);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('commune')) {
            $query->where('commune', 'like', '%' . $request->commune . '%');
        }
        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', '>=', $request->bedrooms);
        }
        if ($request->filled('bathrooms')) {
            $query->where('bathrooms', '>=', $request->bathrooms);
        }
        if ($request->filled('min_price') && is_numeric($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price') && is_numeric($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }

        // 3. Ejecutar la consulta y paginar los resultados
        $properties = $query->latest()->paginate(9)->appends($request->all());

        // 4. Obtener los datos para poblar los selects del formulario de filtros
        $filterData = [
            'categories' => Category::orderBy('name')->get(),
            'communes' => Property::where('status', 'Disponible')->whereNotNull('commune')->distinct()->orderBy('commune')->pluck('commune'),
        ];
        
        // 5. Devolver la vista con las propiedades filtradas y los datos para los filtros
        return view('public.properties.index', compact('properties', 'filterData'));
    }

    /**
     * Muestra el detalle de una propiedad específica.
     * (Este método no se ha modificado).
     */
    public function showProperty(Property $property)
    {
        $property->load(['user', 'category', 'photos', 'videos', 'features', 'customFieldValues.definition']);
        return view('public.properties.show', compact('property'));
    }

    /**
     * Muestra la página "Quiénes Somos".
     * (Este método no se ha modificado).
     */
    public function about()
    {
        return view('public.about');
    }

    /**
     * Muestra la página de "Contacto".
     * (Este método no se ha modificado).
     */
    public function contact()
    {
        return view('public.contact');
    }
}