@extends('layouts.admin')

@section('title', 'Crear Definición de Campo Personalizado - Vive Home')

@section('header')
    {{ __('Crear Nueva Definición de Campo Personalizado') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Formulario de Creación</h4>
        </div>
        <div class="card-body">
        <form action="{{ route('admin.custom_fields.store') }}" method="POST">                {{-- Pasamos una nueva instancia vacía de CustomFieldDefinition al parcial --}}
                {{-- y los $fieldTypes que vienen del controlador --}}
                @include('admin.custom-field-definitions._form', ['definition' => new \App\Models\CustomFieldDefinition(), 'fieldTypes' => $fieldTypes])
            </form>
        </div>
    </div>
@endsection