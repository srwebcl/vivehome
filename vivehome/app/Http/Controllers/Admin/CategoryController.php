<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $categories = Category::withCount('properties') // Carga el conteo de propiedades relacionadas
                          ->orderBy('name')
                          ->paginate(10);
    return view('admin.categories.index', compact('categories'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
        // Buscará una vista en resources/views/admin/categories/create.blade.php
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validación
        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Category::class) // Asegura que el nombre de la categoría sea único
            ],
            // No validamos slug aquí porque lo generaremos
        ]);

        // 2. Crear la categoría
        try {
            Category::create([
                'name' => $validatedData['name'],
                'slug' => Str::slug($validatedData['name']), // Generar slug a partir del nombre
            ]);

            // 3. Redirigir con mensaje de éxito
            return redirect()->route('admin.categories.index')
                             ->with('success', 'Categoría creada exitosamente.');

        } catch (\Exception $e) {
            // \Illuminate\Support\Facades\Log::error('Error al crear categoría: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Hubo un problema al crear la categoría. Por favor, inténtalo de nuevo.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return redirect()->route('admin.categories.edit', $category);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category) // Laravel inyecta la instancia de Category
    {
        return view('admin.categories.edit', compact('category'));
        // Buscará una vista en resources/views/admin/categories/edit.blade.php
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category) // Laravel inyecta la Category a actualizar
    {
        // 1. Validación
        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Category::class)->ignore($category->id) // Ignorar la categoría actual al verificar unicidad del nombre
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash', // Permite alfanuméricos, guiones y guiones bajos
                Rule::unique(Category::class)->ignore($category->id) // Ignorar la categoría actual al verificar unicidad del slug
            ],
        ]);

        // 2. Actualizar la categoría
        try {
            // Si el nombre cambió y el slug no fue modificado manualmente para ser diferente,
            // podríamos considerar regenerar el slug. Pero por ahora, actualizamos con lo que venga del form.
            // Si el slug no se envió o está vacío, y el nombre cambió, regenerar slug.
            $newSlug = $validatedData['slug'];
            if (empty($newSlug) || ($validatedData['name'] !== $category->name && $validatedData['slug'] === $category->slug)) {
                $newSlug = Str::slug($validatedData['name']);
            }


            $category->update([
                'name' => $validatedData['name'],
                'slug' => $newSlug, // Usar el slug validado o regenerado
            ]);

            // 3. Redirigir con mensaje de éxito
            return redirect()->route('admin.categories.index')
                             ->with('success', 'Categoría actualizada exitosamente.');

        } catch (\Exception $e) {
            // \Illuminate\Support\Facades\Log::error('Error al actualizar categoría: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Hubo un problema al actualizar la categoría. Por favor, inténtalo de nuevo.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category) // Laravel inyecta la Category a eliminar
    {
        // Antes de eliminar, podríamos verificar si la categoría tiene propiedades asociadas.
        // La restricción de clave foránea en la migración de 'properties' para 'category_id'
        // es onDelete('restrict'), lo que significa que la base de datos impedirá
        // eliminar una categoría si tiene propiedades. Esto es bueno para la integridad.
        // Si quisiéramos permitirlo y, por ejemplo, poner las propiedades en 'sin categoría'
        // o eliminarlas en cascada, tendríamos que cambiar la migración y la lógica aquí.
        // Por ahora, confiaremos en la restricción de la BD.

        // Si la categoría tiene propiedades, la restricción de BD fallará y lanzará una QueryException.
        // Podemos intentar capturar eso para un mensaje más amigable.
        if ($category->properties()->count() > 0) {
            return redirect()->route('admin.categories.index')
                             ->with('error', "No se puede eliminar la categoría '{$category->name}' porque tiene propiedades asociadas. Por favor, reasigne o elimine esas propiedades primero.");
        }

        try {
            $categoryName = $category->name; // Guardar el nombre para el mensaje
            $category->delete(); // Elimina la categoría de la base de datos

            return redirect()->route('admin.categories.index')
                             ->with('success', "Categoría '{$categoryName}' eliminada exitosamente.");

        } catch (\Illuminate\Database\QueryException $e) {
            // Esto podría capturar el error si la restricción de la BD impide la eliminación
            // (aunque la verificación de arriba debería prevenirlo para nuestro caso de 'restrict')
            // Log::error('Error de BD al eliminar categoría: ' . $e->getMessage());
            return redirect()->route('admin.categories.index')
                             ->with('error', "No se pudo eliminar la categoría '{$category->name}'. Es posible que aún tenga propiedades asociadas o haya ocurrido un error en la base de datos.");
        } catch (\Exception $e) {
            // Log::error('Error general al eliminar categoría: ' . $e->getMessage());
            return redirect()->route('admin.categories.index')
                             ->with('error', 'Hubo un problema al eliminar la categoría. Por favor, inténtalo de nuevo.');
        }
    }
}
