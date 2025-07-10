<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting; // Importa el modelo Setting
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    /**
     * Muestra el formulario para editar las configuraciones del sitio.
     */
    public function edit()
    {
        // Obtenemos todas las configuraciones, agrupadas por su campo 'group' y ordenadas por 'name'
        $settingsByGroup = Setting::orderBy('group')->orderBy('name')->get()->groupBy('group');

        return view('admin.settings.edit', compact('settingsByGroup'));
        // La vista estará en resources/views/admin/settings/edit.blade.php
    }

    /**
     * Actualiza las configuraciones del sitio en la base de datos.
     */
    public function update(Request $request)
    {
        // Validación preliminar (podemos mejorarla)
        // Los valores de las configuraciones vendrán en un array asociativo 'settings'
        // Ejemplo: $request->input('settings') podría ser ['max_photos_per_property' => '20', 'max_videos_per_property' => '2']
        $validatedIncomingSettings = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable|string|max:255', // Validación base para cada valor de configuración
        ]);

        $currentSettings = Setting::all()->keyBy('key');
        $rules = [];
        $inputSettings = $validatedIncomingSettings['settings'];

        // Construir reglas de validación dinámicas basadas en el tipo de cada configuración
        foreach ($currentSettings as $key => $setting) {
            if (isset($inputSettings[$key])) { // Solo validar si el setting fue enviado
                $rule = ['nullable', 'string', 'max:255']; // Regla base
                switch ($setting->type) {
                    case 'integer':
                        $rule = ['nullable', 'integer', 'min:0']; // Ejemplo: enteros no negativos
                        break;
                    case 'boolean':
                        // Para booleanos, esperamos '1' (true) o '0' (false) desde el formulario
                        $rule = ['nullable', 'boolean'];
                        break;
                    // Añadir más casos para 'text', 'json', etc., si se necesitan validaciones específicas
                }
                $rules['settings.' . $key] = $rule;
            }
        }

        // Validar con las reglas dinámicas
        $request->validate($rules);

        try {
            foreach ($inputSettings as $key => $value) {
                $settingToUpdate = $currentSettings->get($key);
                if ($settingToUpdate) {
                    // Para booleanos, si el checkbox no está marcado, no vendrá en el request.
                    // O si usamos un select '0'/'1', el valor será '0' o '1'.
                    if ($settingToUpdate->type === 'boolean') {
                        // Si el campo 'settings[key]' no existe en el request (checkbox no marcado), se asume false ('0')
                        // Si usas un select '0'/'1', esto ya viene como '0' o '1'.
                        $actualValue = array_key_exists($key, $inputSettings) ? ($value ? '1' : '0') : '0';
                        if ($value === null && !array_key_exists($key, $inputSettings) && $settingToUpdate->type === 'boolean') {
                            // Específico para checkboxes no enviados
                            $actualValue = '0';
                        } else {
                            $actualValue = $value ?? '0'; // Si es null y es booleano, default a '0'
                        }

                    } else {
                        $actualValue = $value;
                    }

                    $settingToUpdate->value = $actualValue;
                    $settingToUpdate->save();
                }
            }

            Log::info('Configuraciones del sitio actualizadas por el usuario: ' . auth()->user()->name);
            return redirect()->route('admin.settings.edit')
                             ->with('success', 'Configuraciones del sitio actualizadas exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error al actualizar las configuraciones del sitio: ' . $e->getMessage() . "\nStack Trace:\n" . $e->getTraceAsString());
            return redirect()->route('admin.settings.edit')
                             ->with('error', 'Hubo un problema al actualizar las configuraciones. Por favor, intente más tarde.');
        }
    }
}