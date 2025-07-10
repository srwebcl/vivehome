{{-- En resources/views/admin/all-properties/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Todas las Propiedades - Admin Vive Home')

@section('header')
    {{ __('Gestión de Todas las Propiedades') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Listado de Propiedades del Sistema</h4>
                <a href="{{ route('admin.all-properties.create') }}" class="btn btn-success">
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

            {{-- Formulario de Filtros --}}
            <form method="GET" action="{{ route('admin.all-properties.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Estado</label>
                        <select name="status" id="status" class="form-select form-select-sm">
                            <option value="">Todos</option>
                            @foreach($statuses as $statusValue)
                                <option value="{{ $statusValue }}" {{ request('status') == $statusValue ? 'selected' : '' }}>
                                    {{ ucfirst($statusValue) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="asesor_id" class="form-label">Asesor</label>
                        <select name="asesor_id" id="asesor_id" class="form-select form-select-sm">
                            <option value="">Todos</option>
                            @foreach($asesores as $id => $name)
                                <option value="{{ $id }}" {{ request('asesor_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="category_id" class="form-label">Categoría</label>
                        <select name="category_id" id="category_id" class="form-select form-select-sm">
                            <option value="">Todas</option>
                            @foreach($categories as $id => $name)
                                <option value="{{ $id }}" {{ request('category_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="operation_type" class="form-label">Tipo Operación</label>
                        <select name="operation_type" id="operation_type" class="form-select form-select-sm">
                            <option value="">Ambas</option>
                            @foreach($typeOperations as $typeOp)
                                <option value="{{ $typeOp }}" {{ request('operation_type') == $typeOp ? 'selected' : '' }}>
                                    {{ ucfirst($typeOp) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
                        <a href="{{ route('admin.all-properties.index') }}" class="btn btn-secondary btn-sm">Limpiar</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Asesor</th>
                            <th>Categoría</th>
                            <th>Operación</th>
                            <th>Precio</th>
                            <th>Estado (Actualizar)</th>
                            <th>Fecha Creación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($properties as $property)
                            <tr>
                                <td>{{ $property->id }}</td>
                                <td>
                                    <a href="{{ route('admin.all-properties.show', $property->id) }}">{{ Str::limit($property->title, 40) }}</a>
                                </td>
                                <td>{{ $property->user->name ?? 'N/A' }}</td>
                                <td>{{ $property->category->name ?? 'N/A' }}</td>
                                <td>{{ ucfirst($property->operation_type) }}</td>
                                <td>{{ $property->price ? ($property->currency ?? 'CLP') . ' ' . number_format($property->price, 0, ',', '.') : 'N/A' }}</td>
                                <td>
                                    <form action="{{ route('admin.all-properties.updateStatus', $property->id) }}" method="POST" class="d-inline-flex align-items-center">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-select form-select-sm" style="width: auto; min-width: 120px;" onchange="this.form.submit()" title="Cambiar estado de la propiedad">
                                            <option value="Disponible" {{ $property->status == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                                            <option value="Arrendada" {{ $property->status == 'Arrendada' ? 'selected' : '' }}>Arrendada</option>
                                            <option value="Vendida" {{ $property->status == 'Vendida' ? 'selected' : '' }}>Vendida</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-outline-primary ms-1" title="Guardar cambio de estado">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                    <span class="badge bg-{{ strtolower($property->status) == 'disponible' ? 'success' : (strtolower($property->status) == 'vendida' || strtolower($property->status) == 'arrendada' ? 'danger' : 'secondary') }} ms-2">
                                        {{ $property->status ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>{{ $property->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.all-properties.show', $property->id) }}" class="btn btn-sm btn-info me-1" title="Ver Detalle"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('admin.all-properties.edit', $property->id) }}" class="btn btn-sm btn-primary me-1" title="Editar"><i class="bi bi-pencil-square"></i></a>
                                    <form action="{{ route('admin.all-properties.destroy', $property->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás realmente seguro de que quieres eliminar esta propiedad? Esta acción no se puede deshacer.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar Propiedad">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No se encontraron propiedades.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($properties->hasPages())
                <div class="mt-3">
                    {{ $properties->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection