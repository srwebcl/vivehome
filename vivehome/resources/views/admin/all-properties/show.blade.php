@extends('layouts.admin')

@section('title', 'Detalle de Propiedad: ' . $property->title)

@section('header')
    {{ __('Detalle de Propiedad: ') }} {{ $property->title }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Información Completa</h4>
            <a href="{{ route('admin.all-properties.index') }}" class="btn btn-secondary">Volver al Listado</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    {{-- Datos Principales --}}
                    <h5>Datos Principales</h5>
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th style="width: 30%;">ID Propiedad:</th>
                                <td>{{ $property->id }}</td>
                            </tr>
                            <tr>
                                <th>Título Descriptivo:</th>
                                <td>{{ $property->title }}</td>
                            </tr>
                            <tr>
                                <th>Asesor Inmobiliario:</th>
                                <td>{{ $property->user->name ?? 'No asignado' }} ({{ $property->user->email ?? '' }})</td>
                            </tr>
                            <tr>
                                <th>Categoría:</th>
                                <td>{{ $property->category->name ?? 'No asignada' }}</td>
                            </tr>
                            <tr>
                                <th>Tipo de Operación:</th>
                                <td>{{ ucfirst($property->operation_type) }}</td>
                            </tr>
                            <tr>
                                <th>Tipo de Propiedad:</th>
                                <td>{{ $property->category->name ?? 'No asignada' }}</td>
                            </tr>
                            <tr>
                                <th>Precio:</th>
                                <td>{{ $property->currency ?? 'CLP' }} {{ number_format($property->price, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Estado Actual:</th>
                                <td><span class="badge bg-info">{{ $property->status ?? 'No definido' }}</span></td>
                            </tr>
                            <tr>
                                <th>Dirección Completa:</th>
                                <td>{{ $property->address ?? 'No especificada' }}, {{ $property->commune ?? '' }}, {{ $property->city ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Descripción Detallada:</th>
                                <td>{!! nl2br(e($property->description)) !!}</td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- Dimensiones y Características --}}
                    <h5 class="mt-4">Dimensiones y Características</h5>
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr><th style="width: 30%;">Superficie Total:</th><td>{{ $property->total_area_m2 ?? 'N/A' }} m²</td></tr>
                            <tr><th>Superficie Construida:</th><td>{{ $property->built_area_m2 ?? 'N/A' }} m²</td></tr>
                            <tr><th>Número de Dormitorios:</th><td>{{ $property->bedrooms ?? 'N/A' }}</td></tr>
                            <tr><th>Número de Baños:</th><td>{{ $property->bathrooms ?? 'N/A' }}</td></tr>
                            <tr><th>Estacionamientos:</th><td>{{ $property->parking_lots ?? 'N/A' }}</td></tr>
                            <tr><th>Bodegas:</th><td>{{ $property->storage_units ?? 'N/A' }}</td></tr>
                        </tbody>
                    </table>

                    {{-- Características Adicionales --}}
                    @if($property->features && $property->features->count() > 0)
                        <h5 class="mt-4">Características Adicionales</h5>
                        <ul class="list-group">
                            @foreach($property->features as $feature)
                                <li class="list-group-item">{{ $feature->name }}</li>
                            @endforeach
                        </ul>
                    @endif

                    {{-- Campos Personalizados --}}
                    @if($property->customFieldValues && $property->customFieldValues->count() > 0)
                        <h5 class="mt-4">Campos Personalizados</h5>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                @foreach($property->customFieldValues as $customValue)
                                    <tr>
                                        <th style="width: 30%;">{{ $customValue->customFieldDefinition->name ?? 'Campo Desconocido' }}:</th>
                                        <td>{{ $customValue->value }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                {{-- Columna Derecha - Multimedia y Mapa --}}
                <div class="col-md-4">
                    {{-- Fotos --}}
                    @if($property->photos && $property->photos->count() > 0)
                        <h5 class="mt-4 mt-md-0">Fotos</h5>
                        <div class="row g-2">
                            @foreach($property->photos as $photo)
                                <div class="col-6 col-md-12 col-lg-6">
                                    <img src="{{ Storage::url($photo->file_path) }}" alt="Foto de la propiedad" class="img-fluid rounded mb-2" style="width: 100%; height: 150px; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>No hay fotos disponibles para esta propiedad.</p>
                    @endif
                    
                    {{-- Videos --}}
                    @if($property->videos && $property->videos->count() > 0)
                        <h5 class="mt-4">Videos</h5>
                        @foreach($property->videos as $video)
                            <a href="{{ $video->video_url }}" target="_blank" class="btn btn-secondary btn-sm">
                                Ver Video <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        @endforeach
                    @else
                        <p class="mt-3">No hay videos disponibles para esta propiedad.</p>
                    @endif

                    {{-- Mapa --}}
                    <h5 class="mt-4">Ubicación en Mapa (Próximamente)</h5>
                    <div id="map" style="height: 250px; background-color: #e9e9e9;" class="border rounded d-flex align-items-center justify-content-center">
                        <small>Visualización de mapa no implementada aún.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection