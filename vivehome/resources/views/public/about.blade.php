@extends('layouts.public')

@section('title', 'Quiénes Somos - Vive Home')

@section('content')
    {{-- Encabezado de la sección --}}
    <div class="py-5 bg-light border-bottom">
        <div class="container">
            <h1 class="display-5 fw-bold">Sobre Vive Home</h1>
            <p class="col-md-8 fs-4">Conoce nuestra historia, nuestra misión y al equipo que hace posible encontrar tu lugar ideal.</p>
        </div>
    </div>

    <div class="container my-5">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <h2>Nuestra Misión</h2>
                <p class="lead">Entregar un servicio de asesoría inmobiliaria de excelencia, basado en la confianza, la transparencia y el profundo conocimiento del mercado local. Nuestra meta es superar las expectativas de nuestros clientes en cada etapa del proceso.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed et diam et justo sodales scelerisque. Curabitur vel turpis nec odio interdum consequat. Vivamus in dolor sed ligula consectetur vestibulum. Fusce vel ex vitae augue pulvinar commodo.</p>
            </div>
            <div class="col-md-6">
                {{-- Puedes reemplazar esta imagen por una foto del equipo o de la oficina --}}
                <img src="https://via.placeholder.com/600x400.png?text=Nuestro+Equipo" class="img-fluid rounded shadow-sm" alt="Equipo de Vive Home">
            </div>
        </div>

        <hr class="my-5">

        <div class="row text-center">
            <div class="col-md-4">
                <div class="card border-0">
                    <div class="card-body">
                        <i class="bi bi-bullseye fs-1 text-primary"></i>
                        <h4 class="card-title mt-3">Visión</h4>
                        <p class="card-text">Ser la corredora de propiedades líder y de mayor confianza en la IV Región, reconocida por nuestra integridad, profesionalismo y resultados excepcionales para nuestros clientes.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                 <div class="card border-0">
                    <div class="card-body">
                        <i class="bi bi-gem fs-1 text-primary"></i>
                        <h4 class="card-title mt-3">Valores</h4>
                        <p class="card-text">Compromiso, honestidad, pasión por el servicio y una dedicación constante a la innovación para ofrecer la mejor experiencia inmobiliaria posible.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                 <div class="card border-0">
                    <div class="card-body">
                        <i class="bi bi-people-fill fs-1 text-primary"></i>
                        <h4 class="card-title mt-3">Equipo</h4>
                        <p class="card-text">Contamos con un equipo de asesores expertos y apasionados, listos para guiarte y apoyarte en la importante decisión de tu vida.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection