@extends('layouts.admin')

@section('title', 'Editar Propiedad - Vive Home')

@section('header')
    {{ __('Editar Propiedad: ') }} {{ $property->title }}
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Modifica los datos de la propiedad</h4>
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

        <form action="{{ route('asesor.properties.update', $property) }}" method="POST" enctype="multipart/form-data">
            @csrf  {{-- <--- TOKEN CSRF CORREGIDO --}}
            @method('PUT')
            @include('asesor.properties._form', ['property' => $property])
        </form>
        {{-- FIN: Corrección de Errores y CSRF --}}
    </div>
</div>
@endsection