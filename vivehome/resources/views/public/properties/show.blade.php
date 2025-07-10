@extends('layouts.public')

{{-- El título de la página será el título de la propiedad --}}
@section('title', $property->title)
@section('description', Str::limit($property->description, 155))

@section('content')

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8">
            {{-- Título y Ubicación --}}
            <h1 class="display-5 fw-bold">{{ $property->title }}</h1>
            <p class="fs-5 text-muted"><i class="bi bi-geo-alt-fill me-2"></i>{{ $property->address }}, {{ $property->commune }}, {{ $property->region }}</p>

            <hr>

            {{-- Galería de Fotos (Carrusel de Bootstrap) --}}
            @if($property->photos->isNotEmpty())
                <div id="property-carousel" class="carousel slide shadow-sm rounded mb-4" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        @foreach($property->photos as $key => $photo)
                            <button type="button" data-bs-target="#property-carousel" data-bs-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}" aria-current="{{ $key == 0 ? 'true' : 'false' }}" aria-label="Slide {{ $key + 1 }}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner rounded">
                        @foreach($property->photos as $key => $photo)
                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                <img src="{{ Storage::url($photo->file_path) }}" class="d-block w-100 property-gallery-img" alt="Foto {{ $key + 1 }} de {{ $property->title }}">
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#property-carousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#property-carousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                </div>
            @endif

            {{-- Descripción de la Propiedad --}}
            <h3 class="mt-5">Descripción</h3>
            <p class="lead">{!! nl2br(e($property->description)) !!}</p>

            {{-- Videos --}}
            @if($property->videos->isNotEmpty())
                <h3 class="mt-5">Videos</h3>
                @foreach($property->videos as $video)
                    {{-- Aquí podríamos insertar un reproductor embebido en el futuro --}}
                    <a href="{{ $video->video_url }}" target="_blank" class="btn btn-secondary"><i class="bi bi-youtube me-2"></i>Ver Video</a>
                @endforeach
            @endif

        </div>

        {{-- Barra Lateral Derecha --}}
        <div class="col-lg-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header text-center bg-primary text-white">
                    <h3 class="mb-0">
                        {{ $property->currency == 'UF' ? 'UF' : '$' }} {{ number_format($property->price, $property->currency == 'UF' ? 2 : 0, ',', '.') }}
                    </h3>
                    <span class="badge bg-light text-dark fs-6">{{ $property->operation_type }}</span>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Contactar al Asesor</h5>
                    <p class="text-muted">¿Te interesa esta propiedad? Envíanos un mensaje.</p>
                    
                    {{-- Formulario de Contacto (Placeholder) --}}
                    <form>
                        <div class="mb-3">
                            <label for="contact-name" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="contact-name" required>
                        </div>
                        <div class="mb-3">
                            <label for="contact-email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="contact-email" required>
                        </div>
                         <div class="mb-3">
                            <label for="contact-phone" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="contact-phone">
                        </div>
                        <div class="mb-3">
                            <label for="contact-message" class="form-label">Mensaje</label>
                            <textarea class="form-control" id="contact-message" rows="4" required>Hola, me interesa la propiedad "{{ $property->title }}" (ID: {{ $property->id }}) y quisiera más información. Gracias.</textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Enviar Mensaje</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Fila Adicional para Detalles y Mapa --}}
    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="mb-0">Características y Detalles</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- Detalles Principales --}}
                        <div class="col-md-6">
                            <h4>Generales</h4>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between"><strong>Superficie Construida:</strong> <span>{{$property->built_area_m2}} m²</span></li>
                                <li class="list-group-item d-flex justify-content-between"><strong>Superficie Total:</strong> <span>{{$property->total_area_m2}} m²</span></li>
                                <li class="list-group-item d-flex justify-content-between"><strong>Dormitorios:</strong> <span>{{$property->bedrooms}}</span></li>
                                <li class="list-group-item d-flex justify-content-between"><strong>Baños:</strong> <span>{{$property->bathrooms}}</span></li>
                                <li class="list-group-item d-flex justify-content-between"><strong>Estacionamientos:</strong> <span>{{$property->parking_lots}}</span></li>
                            </ul>
                        </div>
                        {{-- Características Adicionales --}}
                        @if($property->features->isNotEmpty())
                        <div class="col-md-6">
                            <h4>Otras Características</h4>
                            <div class="row">
                                @foreach($property->features as $feature)
                                    <div class="col-6"><i class="bi bi-check-circle-fill text-success me-2"></i>{{$feature->name}}</div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Campos Personalizados --}}
                     @if($property->customFieldValues->isNotEmpty())
                        <hr class="my-4">
                        <h4>Detalles Adicionales</h4>
                        <div class="row">
                            @foreach($property->customFieldValues as $value)
                                <div class="col-md-4"><strong>{{ $value->definition->name }}:</strong> {{ $value->value }}</div>
                            @endforeach
                        </div>
                     @endif
                </div>
            </div>
        </div>

        {{-- Mapa de Ubicación --}}
        <div class="col-12 mt-4">
             <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="mb-0">Ubicación</h3>
                </div>
                <div class="card-body p-0">
                    {{-- Placeholder para el mapa. La integración real se hará después. --}}
                    <div id="map-placeholder" style="height: 400px; background-color: #e9ecef;" class="d-flex align-items-center justify-content-center">
                        <p class="text-muted">Mapa de ubicación (Integración pendiente)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .property-gallery-img {
        height: 500px;
        object-fit: cover;
    }
    .sticky-top {
        /* Pequeño ajuste para que no choque con el nav en pantallas pequeñas */
        top: 80px !important;
    }
</style>
@endpush