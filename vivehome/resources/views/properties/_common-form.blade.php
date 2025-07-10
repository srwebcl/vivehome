{{-- Este es el formulario base con todos los campos comunes --}}
{{-- NO LLEVA @csrf aquí --}}

{{-- Fila: Título y Tipo de Operación --}}
<div class="row mb-3">
    <div class="col-md-8">
        <label for="title" class="form-label">Título de la Propiedad <span class="text-danger">*</span></label>
        <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $property->title ?? '') }}" required>
        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label for="operation_type" class="form-label">Tipo de Operación <span class="text-danger">*</span></label>
        <select id="operation_type" name="operation_type" class="form-select @error('operation_type') is-invalid @enderror" required>
            <option value="Venta" @selected(old('operation_type', $property->operation_type ?? '') == 'Venta')>Venta</option>
            <option value="Arriendo" @selected(old('operation_type', $property->operation_type ?? '') == 'Arriendo')>Arriendo</option>
        </select>
        @error('operation_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

{{-- ... (resto de los campos del formulario: categoría, precio, ubicación, etc.) ... --}}
{{-- Fila 2: Categoría, Precio y Moneda --}}
<div class="row mb-3">
    <div class="col-md-4">
        <label for="category_id" class="form-label">Tipo de Propiedad <span class="text-danger">*</span></label>
        <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
            <option value="">Seleccione un tipo...</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $property->category_id ?? '') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
         @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label for="price" class="form-label">Precio <span class="text-danger">*</span></label>
        <input type="number" id="price" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $property->price ?? '') }}" required>
        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label for="currency" class="form-label">Moneda <span class="text-danger">*</span></label>
        <select id="currency" name="currency" class="form-select @error('currency') is-invalid @enderror" required>
            <option value="CLP" @selected(old('currency', $property->currency ?? 'CLP') == 'CLP')>CLP</option>
            <option value="UF" @selected(old('currency', $property->currency ?? '') == 'UF')>UF</option>
        </select>
        @error('currency') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

{{-- Ubicación --}}
<div class="row mb-3">
    <div class="col-12">
        <label for="address" class="form-label">Dirección</label>
        <input type="text" id="address" name="address" class="form-control" value="{{ old('address', $property->address ?? '') }}">
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-4"><label for="commune" class="form-label">Comuna</label><input type="text" id="commune" name="commune" class="form-control" value="{{ old('commune', $property->commune ?? '') }}"></div>
    <div class="col-md-4"><label for="city" class="form-label">Ciudad</label><input type="text" id="city" name="city" class="form-control" value="{{ old('city', $property->city ?? '') }}"></div>
    <div class="col-md-4"><label for="region" class="form-label">Región</label><input type="text" id="region" name="region" class="form-control" value="{{ old('region', $property->region ?? '') }}"></div>
</div>

{{-- Superficies --}}
<div class="row mb-3">
    <div class="col-md-6"><label for="total_area_m2" class="form-label">Superficie Total (m²)</label><input type="number" step="0.01" id="total_area_m2" name="total_area_m2" class="form-control" value="{{ old('total_area_m2', $property->total_area_m2 ?? '') }}"></div>
    <div class="col-md-6"><label for="built_area_m2" class="form-label">Superficie Construida (m²)</label><input type="number" step="0.01" id="built_area_m2" name="built_area_m2" class="form-control" value="{{ old('built_area_m2', $property->built_area_m2 ?? '') }}"></div>
</div>

{{-- Habitaciones, etc. --}}
<div class="row mb-3">
    <div class="col-md-3"><label for="bedrooms" class="form-label">Dormitorios</label><input type="number" id="bedrooms" name="bedrooms" class="form-control" value="{{ old('bedrooms', $property->bedrooms ?? '') }}"></div>
    <div class="col-md-3"><label for="bathrooms" class="form-label">Baños</label><input type="number" id="bathrooms" name="bathrooms" class="form-control" value="{{ old('bathrooms', $property->bathrooms ?? '') }}"></div>
    <div class="col-md-3"><label for="parking_lots" class="form-label">Estacionamientos</label><input type="number" id="parking_lots" name="parking_lots" class="form-control" value="{{ old('parking_lots', $property->parking_lots ?? '0') }}"></div>
    <div class="col-md-3"><label for="storage_units" class="form-label">Bodegas</label><input type="number" id="storage_units" name="storage_units" class="form-control" value="{{ old('storage_units', $property->storage_units ?? '0') }}"></div>
</div>

{{-- Descripción y Destacado --}}
<div class="mb-3">
    <label for="description" class="form-label">Descripción Detallada <span class="text-danger">*</span></label>
    <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="5" required>{{ old('description', $property->description ?? '') }}</textarea>
    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" @checked(old('is_featured', $property->is_featured ?? false))>
        <label class="form-check-label" for="is_featured">
            <strong>Marcar como propiedad destacada</strong>
            <small class="d-block text-muted">Las propiedades destacadas aparecerán en la página de inicio.</small>
        </label>
    </div>
</div>

{{-- Multimedia --}}
<hr class="my-4">
<h5 class="mb-3">Multimedia</h5>
@if(isset($property) && $property->photos->count() > 0)
    <h6>Imágenes Actuales</h6>
    <div class="row g-2 mb-3">
        @foreach($property->photos as $photo)
            <div class="col-6 col-sm-4 col-md-3"><div class="card"><img src="{{ Storage::url($photo->file_path) }}" class="card-img-top" alt="Foto de la propiedad" style="height: 120px; object-fit: cover;"><div class="card-body p-2 text-center"><div class="form-check"><input class="form-check-input" type="checkbox" name="delete_photos[]" value="{{ $photo->id }}" id="delete_photo_{{ $photo->id }}"><label class="form-check-label" for="delete_photo_{{ $photo->id }}">Eliminar</label></div></div></div></div>
        @endforeach
    </div>
@endif
<div class="mb-3">
    <label for="photos" class="form-label">Añadir nuevas imágenes</label>
    <input class="form-control @error('photos.*') is-invalid @enderror @error('photos') is-invalid @enderror" type="file" id="photos" name="photos[]" multiple accept="image/jpeg,image/png,image/webp">
    <small class="form-text text-muted">Puedes seleccionar múltiples imágenes a la vez.</small>
    @error('photos') <div class="invalid-feedback">{{ $message }}</div> @enderror
    @error('photos.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label for="video_url" class="form-label">Enlace de Video (YouTube, Vimeo, etc.)</label>
    <input type="url" class="form-control @error('video_url') is-invalid @enderror" id="video_url" name="video_url" placeholder="https://youtube.com/..." value="{{ old('video_url', $property->videos->first()->video_url ?? '') }}">
    @error('video_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Características y Campos Personalizados --}}
<hr class="my-4">
<h5 class="mb-3">Características Adicionales</h5>
<div class="row">
    @php $checkedFeatures = old('features', isset($property) ? $property->features->pluck('id')->toArray() : []); @endphp
    @foreach($features as $feature)
        <div class="col-md-4 col-sm-6 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="features[]" value="{{ $feature->id }}" id="feature-{{ $feature->id }}" {{ in_array($feature->id, $checkedFeatures) ? 'checked' : '' }}><label class="form-check-label" for="feature-{{ $feature->id }}">{{ $feature->name }}</label></div></div>
    @endforeach
</div>
<hr class="my-4">
<h5 class="mb-3">Campos Personalizados</h5>
<div class="row">
    @php $existingCustomValues = isset($property) ? $property->customFieldValues->pluck('value', 'custom_field_definition_id') : collect(); @endphp
    @foreach($customFields as $field)
        <div class="col-md-6 mb-3">
            <label for="custom_field_{{ $field->id }}" class="form-label">{{ $field->name }}</label>
            @php $value = old('custom_fields.' . $field->id, $existingCustomValues->get($field->id) ?? ''); @endphp
            @if($field->type === 'textarea')
                <textarea class="form-control" id="custom_field_{{ $field->id }}" name="custom_fields[{{ $field->id }}]">{{ $value }}</textarea>
            @elseif($field->type === 'number')
                <input type="number" class="form-control" id="custom_field_{{ $field->id }}" name="custom_fields[{{ $field->id }}]" value="{{ $value }}">
            @else
                <input type="text" class="form-control" id="custom_field_{{ $field->id }}" name="custom_fields[{{ $field->id }}]" value="{{ $value }}">
            @endif
        </div>
    @endforeach
</div>

{{-- Botones de Acción --}}
<hr class="my-4">
<div class="mt-4">
    <button type="submit" class="btn btn-primary">Guardar Propiedad</button>
    <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancelar</a>
</div>