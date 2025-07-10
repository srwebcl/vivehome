@extends('layouts.public')

@section('title', 'Propiedades en Venta y Arriendo')

@section('content')

    {{-- Encabezado de la sección --}}
    <div class="py-5 bg-light border-bottom">
        <div class="container">
            <h1 class="display-5 fw-bold">Nuestras Propiedades</h1>
            <p class="col-md-8 fs-4">Explora nuestro catálogo completo. Usa los filtros para encontrar exactamente lo que necesitas.</p>
        </div>
    </div>
    
    <div class="container my-5">
        <div class="row">
            {{-- ============================================= --}}
            {{-- INICIO: COLUMNA DE FILTROS ACTUALIZADA --}}
            {{-- ============================================= --}}
            <div class="col-lg-3">
                <div class="card shadow-sm mb-4 sticky-top" style="top: 20px;">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-funnel-fill me-2"></i>Filtrar Búsqueda</h5>
                    </div>
                    <div class="card-body">
                        {{-- El formulario usa el método GET y envía los datos a la misma URL --}}
                        <form action="{{ route('public.properties.index') }}" method="GET">
                            {{-- Tipo de Operación --}}
                            <div class="mb-3">
                                <label for="operation_type" class="form-label fw-bold">Operación</label>
                                <select name="operation_type" id="operation_type" class="form-select">
                                    <option value="">Cualquiera</option>
                                    <option value="Venta" {{ request('operation_type') == 'Venta' ? 'selected' : '' }}>Venta</option>
                                    <option value="Arriendo" {{ request('operation_type') == 'Arriendo' ? 'selected' : '' }}>Arriendo</option>
                                </select>
                            </div>

                            {{-- Tipo de Propiedad (Categoría) --}}
                            <div class="mb-3">
                                <label for="category_id" class="form-label fw-bold">Tipo de Propiedad</label>
                                <select name="category_id" id="category_id" class="form-select">
                                    <option value="">Cualquiera</option>
                                    @foreach($filterData['categories'] as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Comuna --}}
                            <div class="mb-3">
                                <label for="commune" class="form-label fw-bold">Comuna</label>
                                <select name="commune" id="commune" class="form-select">
                                    <option value="">Cualquiera</option>
                                    @foreach($filterData['communes'] as $commune)
                                        <option value="{{ $commune }}" {{ request('commune') == $commune ? 'selected' : '' }}>
                                            {{ $commune }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Dormitorios y Baños --}}
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label for="bedrooms" class="form-label fw-bold">Dormitorios</label>
                                    <input type="number" name="bedrooms" id="bedrooms" class="form-control" min="1" placeholder="Mín." value="{{ request('bedrooms') }}">
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="bathrooms" class="form-label fw-bold">Baños</label>
                                    <input type="number" name="bathrooms" id="bathrooms" class="form-control" min="1" placeholder="Mín." value="{{ request('bathrooms') }}">
                                </div>
                            </div>

                            {{-- Rango de Precio --}}
                             <div class="mb-3">
                                <label class="form-label fw-bold">Precio</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="min_price" class="form-control" placeholder="Mínimo" value="{{ request('min_price') }}">
                                    <span class="input-group-text">-</span>
                                    <input type="number" name="max_price" class="form-control" placeholder="Máximo" value="{{ request('max_price') }}">
                                </div>
                            </div>

                            {{-- Botones de Acción --}}
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                                <a href="{{ route('public.properties.index') }}" class="btn btn-secondary">Limpiar Filtros</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- ============================================= --}}
            {{-- FIN: COLUMNA DE FILTROS --}}
            {{-- ============================================= --}}


            {{-- Columna del Listado de Propiedades (sin cambios) --}}
            <div class="col-lg-9">
                <div class="row">
                    @forelse($properties as $property)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <x-property-card :property="$property" />
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-warning text-center" role="alert">
                                <h4>Sin resultados</h4>
                                <p class="mb-0">No se encontraron propiedades que coincidan con tus criterios de búsqueda. Intenta con otros filtros.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                {{-- Paginación --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $properties->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection