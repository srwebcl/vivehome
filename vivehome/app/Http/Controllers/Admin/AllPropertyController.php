<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CustomFieldDefinition;
use App\Models\Property;
use App\Models\PropertyFeature;
use App\Models\PropertyPhoto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AllPropertyController extends Controller
{
    /**
     * Muestra la lista de todas las propiedades con filtros.
     */
    public function index(Request $request)
    {
        $query = Property::with(['user', 'category'])
                         ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('asesor_id')) {
            $query->where('user_id', $request->asesor_id);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('operation_type')) {
            $query->where('operation_type', $request->operation_type);
        }

        $properties = $query->paginate(15)->appends($request->query());

        $filterData = [
            'statuses' => Property::select('status')->distinct()->pluck('status'),
            'asesores' => User::where('role', 'asesor')->orderBy('name')->pluck('name', 'id'),
            'categories' => Category::orderBy('name')->pluck('name', 'id'),
            'typeOperations' => Property::select('operation_type')->distinct()->pluck('operation_type'),
        ];
        
        return view('admin.all-properties.index', compact('properties'), $filterData);
    }

    /**
     * Muestra el formulario para crear una nueva propiedad.
     */
    public function create()
    {
        $data = [
            'categories' => Category::orderBy('name')->get(),
            'features' => PropertyFeature::orderBy('name')->get(),
            'customFields' => CustomFieldDefinition::orderBy('name')->get(),
            'asesores' => User::where('role', 'asesor')->orderBy('name')->get()
        ];
        return view('admin.all-properties.create', $data);
    }

    /**
     * Guarda una nueva propiedad en la base de datos.
     * --- MÉTODO CORREGIDO ---
     */
    public function store(Request $request)
    {
        // La validación se hace primero y por separado.
        // Si falla, Laravel automáticamente redirigirá con los errores.
        $validatedData = $this->validateProperty($request);

        try {
            $propertyData = $validatedData;
            // Se quitan los campos que no pertenecen a la tabla 'properties'
            unset($propertyData['features'], $propertyData['custom_fields'], $propertyData['photos'], $propertyData['video_url']);

            $propertyData['slug'] = Str::slug($propertyData['title']);
            $propertyData['status'] = 'Disponible';
            $propertyData['is_featured'] = $request->boolean('is_featured');
            
            $property = Property::create($propertyData);
            
            $this->syncRelations($property, $request);

            return redirect()->route('admin.all-properties.index')->with('success', '¡Propiedad creada y asignada exitosamente!');
        } catch (\Exception $e) {
            Log::error('Error al crear propiedad por admin: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Hubo un problema al guardar la propiedad. Por favor, revise el log.');
        }
    }

    /**
     * Muestra el formulario para editar una propiedad existente.
     */
    public function edit(Property $property)
    {
        $data = [
            'property' => $property,
            'categories' => Category::orderBy('name')->get(),
            'features' => PropertyFeature::orderBy('name')->get(),
            'customFields' => CustomFieldDefinition::orderBy('name')->get(),
            'asesores' => User::where('role', 'asesor')->orderBy('name')->get()
        ];
        return view('admin.all-properties.edit', $data);
    }

    /**
     * Actualiza una propiedad existente en la base de datos.
     * --- MÉTODO CORREGIDO ---
     */
    public function update(Request $request, Property $property)
    {
        $validatedData = $this->validateProperty($request, $property->id);

        try {
            $propertyData = $validatedData;
            unset($propertyData['features'], $propertyData['custom_fields'], $propertyData['photos'], $propertyData['delete_photos'], $propertyData['video_url']);
            
            if ($property->title !== $propertyData['title']) {
                $propertyData['slug'] = Str::slug($propertyData['title']);
            }
            $propertyData['is_featured'] = $request->boolean('is_featured');
            
            $property->update($propertyData);

            $this->syncRelations($property, $request);

            return redirect()->route('admin.all-properties.index')->with('success', '¡Propiedad actualizada exitosamente!');
        } catch (\Exception $e) {
            Log::error("Error al actualizar propiedad ID {$property->id} por admin: " . $e->getMessage());
            return back()->withInput()->with('error', 'Hubo un problema al actualizar la propiedad. Por favor, revise el log.');
        }
    }

    /**
     * Muestra los detalles de una propiedad específica.
     */
    public function show(Property $property)
    {
        $property->load(['user', 'category', 'photos', 'videos', 'features', 'customFieldValues.definition']);
        return view('admin.all-properties.show', compact('property'));
    }

    /**
     * Actualiza solo el estado de una propiedad.
     */
    public function updateStatus(Request $request, Property $property)
    {
        $request->validate(['status' => ['required', 'string', Rule::in(['Disponible', 'Arrendada', 'Vendida'])]]);
        try {
            $property->update(['status' => $request->status]);
            return redirect()->route('admin.all-properties.index')->with('success', "Estado actualizado exitosamente.");
        } catch (\Exception $e) {
            return back()->with('error', 'Hubo un problema al actualizar el estado.');
        }
    }

    /**
     * Elimina una propiedad.
     */
    public function destroy(Property $property)
    {
        try {
            $propertyTitle = $property->title;
            $property->delete();
            return redirect()->route('admin.all-properties.index')->with('success', "Propiedad \"{$propertyTitle}\" eliminada.");
        } catch (\Exception $e) {
            return back()->with('error', 'Hubo un problema al eliminar la propiedad.');
        }
    }

    // --- MÉTODOS PRIVADOS DE AYUDA (Validador y Sincronizador de Relaciones) ---

    private function validateProperty(Request $request, $propertyId = null)
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'user_id' => ['required', 'exists:users,id', Rule::exists('users', 'id')->where('role', 'asesor')],
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
    }
    
    private function syncRelations(Property $property, Request $request)
    {
        $property->features()->sync($request->input('features', []));

        if ($request->has('custom_fields')) {
            $property->customFieldValues()->delete();
            foreach ($request->custom_fields as $fieldId => $value) {
                if (!is_null($value) && $value !== '') {
                    $property->customFieldValues()->create(['custom_field_definition_id' => $fieldId, 'value' => $value]);
                }
            }
        }

        if ($request->has('delete_photos')) {
            PropertyPhoto::whereIn('id', $request->delete_photos)->get()->each(function ($photo) {
                Storage::disk('public')->delete($photo->file_path);
                $photo->delete();
            });
        }
        
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('properties', 'public');
                $property->photos()->create(['file_path' => $path]);
            }
        }

        if ($request->filled('video_url')) {
            $property->videos()->updateOrCreate([], ['video_url' => $request->video_url]);
        } else {
            $property->videos()->delete();
        }
    }
}