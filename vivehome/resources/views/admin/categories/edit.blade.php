@extends('layouts.admin')

@section('title', 'Editar Categoría: ' . $category->name . ' - Admin Vive Home')

@section('header')
    {{ __('Editar Categoría: ') }} {{ $category->name }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Formulario de Edición de Categoría</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                @csrf <!-- Token CSRF -->
                @method('PUT') <!-- Método HTTP para actualización -->

                <div class="mb-3">
                    <label for="name" class="form-label">Nombre de la Categoría <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="slug" class="form-label">Slug (URL amigable) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $category->slug) }}" required>
                    <small class="form-text text-muted">El slug se usa en las URLs. Debe ser único y contener solo letras minúsculas, números y guiones.</small>
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Actualizar Categoría</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection