@extends('layouts.admin')
// ... (sections title y header) ...
@section('content')
    <div class="card">
        <div class="card-header"><h4>Formulario de Edición</h4></div>
        <div class="card-body">
            <form action="{{ route('admin.custom_fields.update', $customFieldDefinition->id) }}" method="POST">
                @method('PUT')
                @include('admin.custom-field-definitions._form', ['definition' => $customFieldDefinition, 'fieldTypes' => $fieldTypes])
            </form>
        </div>
    </div>
@endsection