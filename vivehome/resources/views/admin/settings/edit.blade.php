{{-- En resources/views/admin/settings/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Configuración del Sitio - Admin Vive Home')

@section('header')
    {{ __('Configuración General del Sitio') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Editar Configuraciones</h4>
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
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>¡Ups! Hubo algunos problemas con tu entrada:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif


            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                @foreach($settingsByGroup as $groupName => $settings)
                    <fieldset class="mb-4">
                        <legend class="h5">{{ $groupName }}</legend>
                        @foreach($settings as $setting)
                            <div class="mb-3 row">
                                <label for="setting-{{ $setting->key }}" class="col-sm-4 col-form-label fw-bold">{{ $setting->name }}</label>
                                <div class="col-sm-8">
                                    @if($setting->type === 'text' || $setting->type === 'textarea')
                                        <textarea class="form-control @error('settings.'.$setting->key) is-invalid @enderror"
                                                  id="setting-{{ $setting->key }}"
                                                  name="settings[{{ $setting->key }}]"
                                                  rows="3">{{ old('settings.'.$setting->key, $setting->value) }}</textarea>
                                    @elseif($setting->type === 'boolean')
                                        <select class="form-select @error('settings.'.$setting->key) is-invalid @enderror"
                                                id="setting-{{ $setting->key }}"
                                                name="settings[{{ $setting->key }}]">
                                            <option value="1" {{ old('settings.'.$setting->key, $setting->value) == '1' ? 'selected' : '' }}>Sí</option>
                                            <option value="0" {{ old('settings.'.$setting->key, $setting->value) == '0' ? 'selected' : '' }}>No</option>
                                        </select>
                                    @elseif($setting->type === 'integer')
                                        <input type="number" class="form-control @error('settings.'.$setting->key) is-invalid @enderror"
                                               id="setting-{{ $setting->key }}"
                                               name="settings[{{ $setting->key }}]"
                                               value="{{ old('settings.'.$setting->key, $setting->value) }}">
                                    @else {{-- Por defecto, 'string' y otros tipos como texto --}}
                                        <input type="text" class="form-control @error('settings.'.$setting->key) is-invalid @enderror"
                                               id="setting-{{ $setting->key }}"
                                               name="settings[{{ $setting->key }}]"
                                               value="{{ old('settings.'.$setting->key, $setting->value) }}">
                                    @endif

                                    @if($setting->description)
                                        <small class="form-text text-muted">{{ $setting->description }}</small>
                                    @endif
                                    @error('settings.'.$setting->key)
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        @endforeach
                    </fieldset>
                @endforeach

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Guardar Configuraciones</button>
                </div>
            </form>
        </div>
    </div>
@endsection