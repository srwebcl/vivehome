@extends('layouts.admin')

@section('title', 'Crear Nueva Propiedad - Vive Home')

@section('header')
    {{ __('Cargar Nueva Propiedad') }}
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Completa los datos de la propiedad</h4>
    </div>
    <div class="card-body">
        {{-- INICIO: Corrección de Errores y CSRF --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <h5 class="alert-heading">¡Ups! Hubo un problema.</h5>
                <p>Por favor, corrige los siguientes errores:</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('asesor.properties.store') }}" method="POST" enctype="multipart/form-data">
            @csrf  {{-- <--- TOKEN CSRF CORREGIDO --}}
            @include('asesor.properties._form', ['property' => new \App\Models\Property()])
        </form>
        {{-- FIN: Corrección de Errores y CSRF --}}
    </div>
</div>
@endsection