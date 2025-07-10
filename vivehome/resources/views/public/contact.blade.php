@extends('layouts.public')

@section('title', 'Contacto - Vive Home')

@section('content')
    {{-- Encabezado de la sección --}}
    <div class="py-5 bg-light border-bottom">
        <div class="container">
            <h1 class="display-5 fw-bold">Contáctanos</h1>
            <p class="col-md-8 fs-4">¿Tienes alguna pregunta o quieres iniciar el proceso de compra/venta? Estamos aquí para ayudarte.</p>
        </div>
    </div>

    <div class="container my-5">
        <div class="row">
            {{-- Formulario de Contacto --}}
            <div class="col-lg-7 mb-4 mb-lg-0">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-4">Envíanos un Mensaje</h3>
                        <form> {{-- El action y method se añadirán después --}}
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nombre Completo</label>
                                    <input type="text" class="form-control" id="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="email" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Asunto</label>
                                <input type="text" class="form-control" id="subject" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Tu Mensaje</label>
                                <textarea class="form-control" id="message" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar Consulta</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Información de Contacto Adicional --}}
            <div class="col-lg-5">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-4">Información de Contacto</h3>
                        <ul class="list-unstyled">
                            <li class="d-flex mb-3">
                                <i class="bi bi-geo-alt-fill fs-4 text-primary me-3"></i>
                                <div>
                                    <strong>Dirección</strong><br>
                                    Avenida del Mar 1234, La Serena, Chile
                                </div>
                            </li>
                            <li class="d-flex mb-3">
                                <i class="bi bi-telephone-fill fs-4 text-primary me-3"></i>
                                <div>
                                    <strong>Teléfono</strong><br>
                                    +56 9 1234 5678
                                </div>
                            </li>
                            <li class="d-flex mb-3">
                                <i class="bi bi-envelope-fill fs-4 text-primary me-3"></i>
                                <div>
                                    <strong>Email</strong><br>
                                    contacto@vivehome.cl
                                </div>
                            </li>
                             <li class="d-flex">
                                <i class="bi bi-clock-fill fs-4 text-primary me-3"></i>
                                <div>
                                    <strong>Horario</strong><br>
                                    Lunes a Viernes: 09:00 - 18:00
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection