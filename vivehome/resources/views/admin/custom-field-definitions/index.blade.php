@extends('layouts.admin')

@section('title', 'Gestionar Campos Personalizados - Admin Vive Home')

@section('header')
    {{ __('Definiciones de Campos Personalizados para Propiedades') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Lista de Definiciones de Campos</h4>
                <a href="{{ route('admin.custom_fields.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Crear Nueva Definición
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
                            <th>ID</th>
                            <th>Nombre del Campo</th>
                            <th>Slug</th>
                            <th>Tipo</th>
                            <th>¿Filtrable?</th>
                            <th>Opciones (JSON)</th>
                            <th>Creada</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customFieldDefinitions as $definition)
                            <tr>
                                <td>{{ $definition->id }}</td>
                                <td>{{ $definition->name }}</td>
                                <td>{{ $definition->slug }}</td>
                                <td>{{ $fieldTypes[$definition->type] ?? $definition->type }}</td>
                                <td>{{ $definition->is_filterable ? 'Sí' : 'No' }}</td>
                                <td>
                                    @if(!empty($definition->options))
                                        <pre style="max-height: 60px; overflow-y: auto; background-color: #f8f9fa; padding: 5px; border-radius: 4px; font-size: 0.8em;">{{ json_encode($definition->options, JSON_PRETTY_PRINT) }}</pre>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $definition->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                <a href="{{ route('admin.custom_fields.edit', $definition->id) }}" class="btn btn-sm btn-primary me-1" title="Editar">                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.custom_fields.destroy', $definition->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta definición de campo?');">                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No se encontraron definiciones de campos personalizados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($customFieldDefinitions->hasPages())
                <div class="mt-3">
                    {{ $customFieldDefinitions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection