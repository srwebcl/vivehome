@csrf <div class="row">
    <div class="col-md-6 mb-3">
        <label for="name" class="form-label">Nombre del Campo <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $definition->name ?? '') }}" required autofocus>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="slug" class="form-label">Slug (Identificador interno)</label>
        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $definition->slug ?? '') }}" aria-describedby="slugHelp">
        <small id="slugHelp" class="form-text text-muted">Dejar en blanco para autogenerar. Usar solo letras minúsculas, números y guiones.</small>
        @error('slug')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label for="type" class="form-label">Tipo de Campo <span class="text-danger">*</span></label>
    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
        <option value="">Seleccione un tipo...</option>
        @foreach($fieldTypes as $typeKey => $typeName)
            <option value="{{ $typeKey }}" {{ old('type', $definition->type ?? '') == $typeKey ? 'selected' : '' }}>
                {{ $typeName }}
            </option>
        @endforeach
    </select>
    @error('type')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3" id="options_field_group" style="{{ in_array(old('type', $definition->type ?? ''), ['select', 'radio', 'checkbox']) ? 'display: block;' : 'display: none;' }}">
    <label for="options" class="form-label">Opciones (JSON)</label>
    <textarea class="form-control @error('options') is-invalid @enderror" id="options" name="options" rows="4" aria-describedby="optionsHelp">{{ old('options', isset($definition->options) ? (is_array($definition->options) ? json_encode($definition->options, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $definition->options) : '') }}</textarea>
    <small id="optionsHelp" class="form-text text-muted">
        Requerido para tipos 'Selección', 'Radio', 'Checkbox (Grupo)'. Formato JSON, ej: <code>{"clave1":"Valor Opción 1", "clave2":"Valor Opción 2"}</code> o para checkboxes grupales <code>{"opcion_a":"Etiqueta A", "opcion_b":"Etiqueta B"}</code>.
    </small>
    @error('options')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" value="1" id="is_filterable" name="is_filterable" {{ old('is_filterable', $definition->is_filterable ?? false) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_filterable">
        ¿Se puede usar como filtro de búsqueda en el sitio público?
    </label>
    @error('is_filterable')
        <div class="invalid-feedback d-block">{{ $message }}</div> {{-- d-block para que se muestre con checkboxes --}}
    @enderror
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-primary">{{ isset($definition) && $definition->exists ? 'Actualizar' : 'Guardar' }} Definición</button>
    <a href="{{ route('admin.custom_fields.index') }}" class="btn btn-secondary">Cancelar</a>
</div>

{{-- Este script se movió a la vista principal index.blade.php y a _form.blade.php para create/edit --}}
{{-- Aquí podemos poner scripts específicos si el _form se usa en contextos sin @push('scripts') --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.getElementById('type');
    const optionsFieldGroup = document.getElementById('options_field_group'); // El div que contiene label y textarea
    const optionsTextarea = document.getElementById('options'); // Específicamente el textarea
    const typesWithOptions = ['select', 'radio', 'checkbox']; // Tipos que requieren el campo de opciones

    function toggleOptionsField() {
        if (optionsFieldGroup && typeSelect && optionsTextarea) { // Asegurarse que los elementos existan
            if (typesWithOptions.includes(typeSelect.value)) {
                optionsFieldGroup.style.display = 'block';
                // No es necesario setear 'required' aquí si la validación del backend es condicional
                // optionsTextarea.setAttribute('required', 'required');
            } else {
                optionsFieldGroup.style.display = 'none';
                // optionsTextarea.removeAttribute('required');
            }
        }
    }

    if (typeSelect) {
        typeSelect.addEventListener('change', toggleOptionsField);
        // Llamar al inicio para establecer el estado correcto al cargar (especialmente en edición)
        toggleOptionsField();
    }
});
</script>