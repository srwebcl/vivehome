@extends('layouts.admin')

@section('title', 'Gestionar Usuarios - Admin Vive Home')

@section('header')
    {{ __('Gestión de Usuarios') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Lista de Usuarios</h4>
                <a href="{{ route('admin.users.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Crear Nuevo Usuario
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

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Registrado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role == 'admin')
                                        <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary me-1" title="Editar">
                                        <i class="bi bi-pencil-square"></i> <!-- Icono Editar -->
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este usuario?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
        <i class="bi bi-trash3"></i>
    </button>
</form>                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No se encontraron usuarios.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="mt-3">
                    {{ $users->links() }} <!-- Paginación de Bootstrap -->
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<!-- Si necesitas JS específico para esta página, puedes añadirlo aquí -->
<!-- Ejemplo: <script> console.log('Página de lista de usuarios cargada'); </script> -->
@endpush