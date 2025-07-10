@extends('layouts.admin')

@section('title', 'Crear Nueva Categoría - Admin Vive Home')

@section('header')
    {{ __('Crear Nueva Categoría de Propiedad') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Formulario de Creación de Categoría</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf <!-- Token CSRF para seguridad -->

                <div class="mb-3">
                    <label for="name" class="form-label">Nombre de la Categoría <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- El slug se generará automáticamente, pero podríamos añadir un campo si quisiéramos permitir edición manual --}}
                {{-- Por ahora, lo omitimos del formulario y lo generamos en el controlador --}}

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Guardar Categoría</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection