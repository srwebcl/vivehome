@extends('layouts.admin')

@section('title', 'Mis Propiedades - Vive Home')

@section('header')
    {{ __('Gestión de Mis Propiedades') }}
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Listado de Mis Propiedades</h4>
                <a href="{{ route('asesor.properties.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Crear Nueva Propiedad
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Título</th>
                            <th>Categoría</th>
                            <th>Operación</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($properties as $property)
                            <tr>
                                <td>
                                    <a href="{{ route('asesor.properties.edit', $property) }}">{{ Str::limit($property->title, 40) }}</a>
                                </td>
                                <td>
                                    {{-- INICIO: CÓDIGO CORREGIDO Y MÁS SEGURO --}}
                                    {{-- Ahora comprobamos si la categoría existe antes de mostrar su nombre. --}}
                                    @if($property->category)
                                        {{ $property->category->name }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                    {{-- FIN: CÓDIGO CORREGIDO --}}
                                </td>
                                <td>{{ ucfirst($property->operation_type) }}</td>
                                <td>{{ $property->currency ?? 'CLP' }} {{ number_format($property->price, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-{{ strtolower($property->status) == 'disponible' ? 'success' : 'secondary' }}">
                                        {{ $property->status ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('public.properties.show', $property->slug) }}" class="btn btn-sm btn-info me-1" title="Ver en Sitio Público" target="_blank">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    <a href="{{ route('asesor.properties.edit', $property) }}" class="btn btn-sm btn-primary me-1" title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('asesor.properties.destroy', $property) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta propiedad?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    No has cargado ninguna propiedad todavía.
                                    <br>
                                    <a href="{{ route('asesor.properties.create') }}" class="btn btn-primary btn-sm mt-2">Crea tu primera propiedad</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($properties->hasPages())
                <div class="mt-3">
                    {{ $properties->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection