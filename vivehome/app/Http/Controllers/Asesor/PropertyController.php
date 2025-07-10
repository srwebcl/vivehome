<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CustomFieldDefinition;
use App\Models\Property;
use App\Models\PropertyFeature;
use App\Models\PropertyPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    /**
     * Muestra TODAS las propiedades a cualquier asesor.
     */
    public function index()
    {
        $properties = Property::with('category', 'user')->latest()->paginate(10);
        return view('asesor.properties.index', compact('properties'));
    }

    /**
     * Muestra el formulario para crear una nueva propiedad.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $features = PropertyFeature::orderBy('name')->get();
        $customFields = CustomFieldDefinition::orderBy('name')->get();
        return view('asesor.properties.create', compact('categories', 'features', 'customFields'));
    }

    /**
     * Guarda una nueva propiedad en la base de datos.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'operation_type' => 'required|in:Venta,Arriendo',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|in:CLP,UF',
            'address' => 'nullable|string|max:255',
            'commune' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'total_area_m2' => 'nullable|numeric|min:0',
            'built_area_m2' => 'nullable|numeric|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'parking_lots' => 'nullable|integer|min:0',
            'storage_units' => 'nullable|integer|min:0',
            'is_featured' => 'nullable|boolean',
            'features' => 'nullable|array',
            'features.*' => 'exists:property_features,id',
            'custom_fields' => 'nullable|array',
            'custom_fields.*' => 'nullable|string|max:65535',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'video_url' => 'nullable|url|max:255',
        ]);

        try {
            $propertyData = collect($validatedData)->except(['features', 'custom_fields', 'photos', 'video_url'])->all();
            $propertyData['user_id'] = Auth::id();
            $propertyData['slug'] = Str::slug($propertyData['title']);
            $propertyData['status'] = 'Disponible';
            $propertyData['is_featured'] = $request->boolean('is_featured');
            
            $property = Property::create($propertyData);
            
            $this->syncRelations($property, $request);

            return redirect()->route('asesor.properties.index')->with('success', '¡Propiedad creada exitosamente!');
        } catch (\Exception $e) {
            Log::error('Error al crear propiedad: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Hubo un problema al guardar la propiedad.');
        }
    }

    /**
     * Redirige a la vista de edición.
     */
    public function show(Property $property)
    {
        return redirect()->route('asesor.properties.edit', $property);
    }

    /**
     * Muestra el formulario para editar una propiedad.
     */
    public function edit(Property $property)
    {
        $categories = Category::orderBy('name')->get();
        $features = PropertyFeature::orderBy('name')->get();
        $customFields = CustomFieldDefinition::orderBy('name')->get();
        return view('asesor.properties.edit', compact('property', 'categories', 'features', 'customFields'));
    }

    /**
     * Actualiza una propiedad existente en la base de datos.
     */
    public function update(Request $request, Property $property)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'operation_type' => 'required|in:Venta,Arriendo',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|in:CLP,UF',
            'address' => 'nullable|string|max:255',
            'commune' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'total_area_m2' => 'nullable|numeric|min:0',
            'built_area_m2' => 'nullable|numeric|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'parking_lots' => 'nullable|integer|min:0',
            'storage_units' => 'nullable|integer|min:0',
            'is_featured' => 'nullable|boolean',
            'features' => 'nullable|array',
            'features.*' => 'exists:property_features,id',
            'custom_fields' => 'nullable|array',
            'custom_fields.*' => 'nullable|string|max:65535',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'delete_photos' => 'nullable|array',
            'delete_photos.*' => 'exists:property_photos,id',
            'video_url' => 'nullable|url|max:255',
        ]);

        try {
            $propertyData = collect($validatedData)->except(['features', 'custom_fields', 'photos', 'delete_photos', 'video_url'])->all();
            if ($property->title !== $propertyData['title']) {
                $propertyData['slug'] = Str::slug($propertyData['title']);
            }
            $propertyData['is_featured'] = $request->boolean('is_featured');
            $property->update($propertyData);

            $this->syncRelations($property, $request);

            return redirect()->route('asesor.properties.index')->with('success', '¡Propiedad actualizada exitosamente!');
        } catch (\Exception $e) {
            Log::error("Error al actualizar propiedad ID {$property->id}: " . $e->getMessage());
            return back()->withInput()->with('error', 'Hubo un problema al actualizar la propiedad.');
        }
    }

    /**
     * Elimina una propiedad.
     */
    public function destroy(Property $property)
    {
        try {
            $property->delete();
            return redirect()->route('asesor.properties.index')->with('success', 'Propiedad eliminada exitosamente.');
        } catch (\Exception $e) {
            Log::error("Error al eliminar propiedad ID {$property->id}: " . $e->getMessage());
            return back()->with('error', 'Hubo un problema al eliminar la propiedad.');
        }
    }

    /**
     * Método privado para sincronizar las relaciones (fotos, videos, características, etc.).
     */
    private function syncRelations(Property $property, Request $request)
    {
        // Sincronizar características (features)
        $property->features()->sync($request->input('features', []));

        // Sincronizar campos personalizados
        if ($request->has('custom_fields')) {
            $property->customFieldValues()->delete(); // Limpia los valores antiguos para evitar duplicados
            foreach ($request->custom_fields as $fieldId => $value) {
                if (!is_null($value) && $value !== '') {
                    $property->customFieldValues()->create([
                        'custom_field_definition_id' => $fieldId,
                        'value' => $value
                    ]);
                }
            }
        }

        // Eliminar fotos marcadas
        if ($request->has('delete_photos')) {
            PropertyPhoto::whereIn('id', $request->delete_photos)->get()->each(function ($photo) {
                Storage::disk('public')->delete($photo->file_path);
                $photo->delete();
            });
        }
        
        // Añadir nuevas fotos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('properties', 'public');
                $property->photos()->create(['file_path' => $path]);
            }
        }

        // Sincronizar video
        if ($request->filled('video_url')) {
            $property->videos()->updateOrCreate([], ['video_url' => $request->video_url]);
        } else {
            $property->videos()->delete();
        }
    }
}