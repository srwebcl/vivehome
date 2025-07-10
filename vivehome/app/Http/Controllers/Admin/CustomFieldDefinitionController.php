<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomFieldDefinition;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log; // Asegúrate de importar Log

class CustomFieldDefinitionController extends Controller
{
    // Helper function para obtener los tipos de campo disponibles
    private function getFieldTypes(): array
    {
        return [
            'text' => 'Texto Corto',
            'textarea' => 'Texto Largo',
            'number' => 'Número',
            'select' => 'Selección Desplegable',
            'radio' => 'Opciones de Radio',
            'checkbox' => 'Casilla de Verificación (Grupo)',
            'date' => 'Fecha',
            'boolean' => 'Booleano (Sí/No)',
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customFieldDefinitions = CustomFieldDefinition::orderBy('name')->paginate(10);
        $fieldTypes = $this->getFieldTypes();
        return view('admin.custom-field-definitions.index', compact('customFieldDefinitions', 'fieldTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fieldTypes = $this->getFieldTypes();
        return view('admin.custom-field-definitions.create', compact('fieldTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fieldTypesKeys = array_keys($this->getFieldTypes());

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique(CustomFieldDefinition::class)],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique(CustomFieldDefinition::class)],
            'type' => ['required', 'string', Rule::in($fieldTypesKeys)],
            'options' => ['nullable', 'json', function ($attribute, $value, $fail) use ($request) {
                if (in_array($request->input('type'), ['select', 'radio', 'checkbox']) && empty($value)) {
                    $fail('El campo Opciones (JSON) es requerido para los tipos de campo: Selección, Radio o Checkbox (Grupo).');
                }
            }],
            'is_filterable' => ['sometimes', 'boolean'],
        ]);

        try {
            $slug = !empty($validatedData['slug']) ? $validatedData['slug'] : Str::slug($validatedData['name']);

            CustomFieldDefinition::create([
                'name' => $validatedData['name'],
                'slug' => $slug,
                'type' => $validatedData['type'],
                'options' => $validatedData['options'] ? json_decode($validatedData['options'], true) : null,
                'is_filterable' => $request->boolean('is_filterable'),
            ]);

            Log::info("CustomFieldDefinition creada exitosamente (Nombre: {$validatedData['name']}).");
            // CORREGIDO: Usar admin.custom_fields.index
            return redirect()->route('admin.custom_fields.index')
                             ->with('success', 'Definición de campo personalizado creada exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error al crear definición de campo personalizado: ' . $e->getMessage() . "\nStack Trace:\n" . $e->getTraceAsString());
            return back()->withInput()->with('error', 'Hubo un problema al crear la definición. Por favor, intente más tarde.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomFieldDefinition $customFieldDefinition)
    {
        // CORREGIDO: Usar admin.custom_fields.edit
        return redirect()->route('admin.custom_fields.edit', $customFieldDefinition);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomFieldDefinition $customFieldDefinition)
    {
        $fieldTypes = $this->getFieldTypes();
        return view('admin.custom-field-definitions.edit', compact('customFieldDefinition', 'fieldTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomFieldDefinition $customFieldDefinition)
    {
        $fieldTypesKeys = array_keys($this->getFieldTypes());

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique(CustomFieldDefinition::class)->ignore($customFieldDefinition->id)],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique(CustomFieldDefinition::class)->ignore($customFieldDefinition->id)],
            'type' => ['required', 'string', Rule::in($fieldTypesKeys)],
            'options' => ['nullable', 'json', function ($attribute, $value, $fail) use ($request) {
                if (in_array($request->input('type'), ['select', 'radio', 'checkbox']) && empty($value)) {
                    $fail('El campo Opciones (JSON) es requerido para los tipos de campo: Selección, Radio o Checkbox (Grupo).');
                }
            }],
            'is_filterable' => ['sometimes', 'boolean'],
        ]);

        try {
            $slug = $customFieldDefinition->slug;
            if (!empty($validatedData['slug']) && $validatedData['slug'] !== $customFieldDefinition->slug) {
                $slug = $validatedData['slug'];
            } elseif ($customFieldDefinition->name !== $validatedData['name'] && (empty($validatedData['slug']) || $validatedData['slug'] === $customFieldDefinition->slug)) {
                $slug = Str::slug($validatedData['name']);
            }

            $newOptions = null;
            if ($request->filled('options') && isset($validatedData['options'])) {
                $newOptions = json_decode($validatedData['options'], true);
            } elseif (!$request->filled('options') && isset($customFieldDefinition->options)) {
                $newOptions = $customFieldDefinition->options;
            }

            if (!in_array($validatedData['type'], ['select', 'radio', 'checkbox'])) {
                $newOptions = null;
            }

            $customFieldDefinition->update([
                'name' => $validatedData['name'],
                'slug' => $slug,
                'type' => $validatedData['type'],
                'options' => $newOptions,
                'is_filterable' => $request->boolean('is_filterable'),
            ]);

            Log::info("CustomFieldDefinition actualizada exitosamente (ID: {$customFieldDefinition->id}).");
            // CORREGIDO: Usar admin.custom_fields.index
            return redirect()->route('admin.custom_fields.index')
                             ->with('success', 'Definición de campo personalizado actualizada exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar definición de campo personalizado (ID: ' . $customFieldDefinition->id . '): ' . $e->getMessage() . "\nStack Trace:\n" . $e->getTraceAsString());
            return back()->withInput()->with('error', 'Hubo un problema al actualizar la definición. Por favor, intente más tarde.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomFieldDefinition $customFieldDefinition)
{
    try {
        // Comprobación: ¿Está esta definición de campo en uso?
        // Usamos el nombre de la relación que definiste: values()
        if ($customFieldDefinition->values()->exists()) { // <<<--- AQUÍ USAS TU RELACIÓN
            Log::warning("Intento de eliminación de CustomFieldDefinition en uso (ID: {$customFieldDefinition->id}, Nombre: \"{$customFieldDefinition->name}\").");
            return redirect()->route('admin.custom_fields.index')
                             ->with('error', 'La definición de campo "' . $customFieldDefinition->name . '" está actualmente en uso por una o más propiedades y no puede ser eliminada.');
        }

        $definitionName = $customFieldDefinition->name;
        $customFieldDefinition->delete();

        Log::info("CustomFieldDefinition eliminada exitosamente (ID: {$customFieldDefinition->id}, Nombre: \"{$definitionName}\").");
        return redirect()->route('admin.custom_fields.index')
                         ->with('success', 'Definición de campo personalizado "' . $definitionName . '" eliminada exitosamente.');

    } catch (\Exception $e) {
        Log::error('Error al eliminar definición de campo personalizado (ID: ' . $customFieldDefinition->id . '): ' . $e->getMessage() . "\nStack Trace:\n" . $e->getTraceAsString());
        return redirect()->route('admin.custom_fields.index')
                         ->with('error', 'Hubo un problema al eliminar la definición. Por favor, intente más tarde.');
    }
}
}