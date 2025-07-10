{{-- Este es el formulario específico del ADMIN --}}
{{-- NO LLEVA @csrf aquí --}}

{{-- CAMPO CLAVE: Selector de Asesor para el Administrador --}}
<div class="row mb-3">
    <div class="col-md-4 offset-md-8">
        <label for="user_id" class="form-label">Asesor Asignado <span class="text-danger">*</span></label>
        <select id="user_id" name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
            <option value="">Seleccione un asesor...</option>
            @foreach($asesores as $asesor)
                <option value="{{ $asesor->id }}" @selected(old('user_id', $property->user_id ?? '') == $asesor->id)>{{ $asesor->name }}</option>
            @endforeach
        </select>
        @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<hr>

{{-- Incluimos el formulario común que acabamos de crear --}}
@include('properties._common-form')