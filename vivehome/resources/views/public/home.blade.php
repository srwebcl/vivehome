@extends('layouts.public')

@section('title', 'Inicio - Vive Home Asesores Inmobiliarios')

@section('content')

    {{-- ============================================= --}}
    {{-- INICIO: SECCIÓN HERO BASADA EN TU CÓDIGO FUNCIONAL --}}
    {{-- ============================================= --}}
    <div id="hero-carousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        {{-- Indicadores del carrusel --}}
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#hero-carousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#hero-carousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#hero-carousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>

        {{-- Contenido del carrusel --}}
        <div class="carousel-inner">
            {{-- Se mantiene la estructura de rutas que te funcionaba --}}
            <div class="carousel-item active" style="background-image: url('/assets/images/fondo-4.webp')">
                <div class="carousel-caption-custom">
                    <h1 class="display-4 fw-bold">Encuentra tu próximo hogar</h1>
                    <p class="lead">La propiedad que buscas en la IV Región está aquí. ¿Qué esperas?</p>
                </div>
            </div>
            <div class="carousel-item" style="background-image: url('/assets/images/fondo-2.webp')">
                 <div class="carousel-caption-custom">
                    <h1 class="display-4 fw-bold">Asesoría de Confianza</h1>
                    <p class="lead">Te acompañamos en cada paso para que tomes la mejor decisión.</p>
                </div>
            </div>
            <div class="carousel-item" style="background-image: url('/assets/images/fondo-6.webp')">
                 <div class="carousel-caption-custom">
                    <h1 class="display-4 fw-bold">Invierte con Seguridad</h1>
                    <p class="lead">Descubre las mejores oportunidades de inversión inmobiliaria.</p>
                </div>
            </div>
        </div>
        
        <button class="carousel-control-prev" type="button" data-bs-target="#hero-carousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#hero-carousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span>
        </button>

        {{-- Formulario de búsqueda (Se mantiene dentro para respetar la estructura original) --}}
        <div class="filter-form-container">
            <div class="container">
                <div class="card p-3 shadow-lg">
                    <span class="d-block mb-2 text-muted">Filtro de búsqueda</span>
                    <form action="{{ route('public.properties.index') }}" method="GET">
                        <div class="row g-2">
                            <div class="col-md-3">
                                <select name="operation_type" class="form-select">
                                    <option value="">Operación</option>
                                    <option value="Venta">Venta</option>
                                    <option value="Arriendo">Arriendo</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="category_id" class="form-select">
                                    <option value="">Tipo de Propiedad</option>
                                     @foreach($filterData['categories'] as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                 <select name="commune" class="form-select">
                                    <option value="">Comuna</option>
                                    @foreach($filterData['communes'] as $commune)
                                        <option value="{{ $commune }}">{{ $commune }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Buscar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- ============================================= --}}
    {{-- FIN: NUEVA SECCIÓN HERO --}}
    {{-- ============================================= --}}


    {{-- Sección de Propiedades Destacadas --}}
    <div class="container my-5">
        <h2 class="text-center mb-4">Propiedades Destacadas</h2>
        
        @if($featuredProperties->isNotEmpty())
            <div class="row">
                @foreach($featuredProperties as $property)
                    <div class="col-md-4 mb-4">
                        <x-property-card :property="$property" />
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-3">
                <a href="{{ route('public.properties.index') }}" class="btn btn-outline-primary">Ver todas las propiedades</a>
            </div>
        @else
            <p class="text-center text-muted">Próximamente tendremos nuevas propiedades destacadas para ti.</p>
        @endif
    </div>

@endsection

{{-- Estilos personalizados para lograr el diseño responsivo --}}
@push('styles')
<style>
    /* Estilos base para el carrusel (Móvil primero) */
    #hero-carousel {
        height: auto; /* La altura se adaptará al contenido */
        position: relative;
    }
    .carousel-item {
        height: 50vh; /* Altura para el slide */
        min-height: 400px;
        background-size: cover;
        background-position: center center;
    }
    .carousel-item::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.4);
    }
    .carousel-caption-custom {
        display: block !important; /* Asegura que el texto sea visible */
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 10;
        color: white;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.7);
        width: 85%;
        text-align: center;
    }
    .carousel-caption-custom h1 { font-size: 2.2rem; }
    
    /* Por defecto (móvil), el filtro no es absoluto */
    .filter-form-container {
        position: static;
        padding: 1.5rem 1rem;
        background-color: #f8f9fa;
    }

    /* --- ESTILOS PARA PANTALLAS GRANDES (Desktop) --- */
    @media (min-width: 992px) {
        #hero-carousel {
            height: 60vh; /* Se restaura la altura fija */
            margin-bottom: 80px; /* Se restaura el margen para el filtro flotante */
        }
        .carousel-item {
            height: 100%;
        }
        .carousel-caption-custom {
            text-align: left;
            left: 15%;
            transform: translateY(-50%);
            width: auto;
        }
        .carousel-caption-custom h1 { font-size: 3.5rem; }

        /* En desktop, el filtro vuelve a ser flotante */
        .filter-form-container {
            position: absolute;
            bottom: -50px;
            left: 0;
            right: 0;
            z-index: 15;
            padding: 0;
            background-color: transparent;
        }
    }
</style>
@endpush