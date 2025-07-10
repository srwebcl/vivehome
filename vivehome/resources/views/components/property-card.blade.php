@props(['property'])

{{-- 
    ================================================================
    INICIO: VERSIÓN FINAL-FINALÍSIMA DE LA TARJETA
    ================================================================
--}}

<div class="card h-100 shadow-sm property-card-pro">
    <a href="{{ route('public.properties.show', $property) }}" class="text-decoration-none text-dark d-flex flex-column h-100">
        
        <div class="position-relative">
            <img src="{{ $property->photos->first() ? Storage::url($property->photos->first()->file_path) : 'https://via.placeholder.com/400x250.png?text=Vive+Home' }}" 
                 class="card-img-top property-card-img" 
                 alt="Foto principal de {{ $property->title }}">
            
            <div class="property-card-tags">
                @php
                    $operationClass = '';
                    switch (strtolower($property->operation_type)) {
                        case 'venta':
                            $operationClass = 'text-bg-success';
                            break;
                        case 'arriendo':
                            $operationClass = 'text-bg-primary';
                            break;
                        default:
                            $operationClass = 'text-bg-secondary';
                            break;
                    }
                @endphp
                <span class="badge {{ $operationClass }}">{{ $property->operation_type }}</span>
                
                <span class="badge badge-property-type">{{ $property->category->name ?? 'Sin Categoría' }}</span>
            </div>
        </div>

        <div class="card-body d-flex flex-column flex-grow-1 p-3 text-center">
            <h5 class="property-card-title">{{ $property->title }}</h5>
            
            <p class="card-text text-muted small mb-2">
                <i class="bi bi-geo-alt-fill me-1"></i>
                {{ $property->commune ?? 'Ubicación no especificada' }}{{ $property->city ? ', ' . $property->city : '' }}
            </p>

            <hr class="property-card-separator">
            
            <h4 class="card-text fw-bold text-primary mt-auto pt-2 mb-1">
                {{ $property->currency == 'UF' ? 'UF' : '$' }} {{ number_format($property->price, 0, ',', '.') }}
            </h4>
        </div>

        <div class="card-footer bg-light border-0 pt-2 pb-3 px-3">
            <div class="d-flex justify-content-around text-center">
                <div class="property-feature" title="Superficie construida">
                    <i class="bi bi-rulers fs-5"></i>
                    <div class="fw-bold">{{ number_format($property->built_area_m2 ?? 0, 0, ',', '.') }} m²</div>
                    <div class="feature-label">Superficie</div>
                </div>
                <div class="property-feature" title="Dormitorios">
                    <i class="bi bi-door-closed-fill fs-5"></i>
                    <div class="fw-bold">{{ $property->bedrooms ?? 'N/A' }}</div>
                    <div class="feature-label">Dorms.</div>
                </div>
                <div class="property-feature" title="Baños">
                    <i class="bi bi-droplet fs-5"></i>
                    <div class="fw-bold">{{ $property->bathrooms ?? 'N/A' }}</div>
                    <div class="feature-label">Baños</div>
                </div>
                <div class="property-feature" title="Estacionamientos">
                    <i class="bi bi-car-front-fill fs-5"></i>
                    <div class="fw-bold">{{ $property->parking_lots ?? '0' }}</div>
                    <div class="feature-label">Estac.</div>
                </div>
            </div>
        </div>
    </a>
</div>

@once
    @push('styles')
    <style>
        .property-card-pro {
            border: 1px solid rgba(var(--bs-primary-rgb), 0.3);
            transition: all 0.3s ease-in-out;
            border-radius: .5rem; 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji";
        }
        .property-card-pro:hover {
            transform: translateY(-8px);
            box-shadow: 0 0.7rem 1.5rem rgba(0, 0, 0, 0.18) !important;
            border-color: rgba(var(--bs-primary-rgb), 0.6);
        }
        .property-card-title {
            font-weight: 600; 
            font-size: 1.05rem;
            line-height: 1.4;
            color: #212529;
            margin-bottom: 0.25rem;
            min-height: 66px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .property-card-img {
            height: 220px;
            object-fit: cover;
            border-top-left-radius: calc(.5rem - 1px);
            border-top-right-radius: calc(.5rem - 1px);
        }
        .property-card-tags {
            position: absolute;
            top: 12px;
            left: 12px;
            z-index: 1;
            text-align: left;
        }
        .property-card-tags .badge {
            font-size: 0.8rem;
            padding: 0.5em 0.8em;
            box-shadow: 0 1px 3px rgba(0,0,0,0.3);
            margin-right: 4px;
        }
        
        /* CORRECCIÓN FINAL: La etiqueta personalizada ahora tiene un fondo sólido. */
        .badge-property-type {
            background-color: #e7f1ff !important; /* Fondo azul pastel sólido y muy claro */
            border: 1px solid #b3d1ff; /* Borde azul más visible */
            color: #034ca1 !important; 
            font-weight: 500;
        }

        .property-feature {
            color: #6c757d;
            font-size: 0.8rem;
        }
        .property-feature .fw-bold {
            color: #343a40;
            font-size: 0.9rem;
        }
        .property-feature .feature-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .property-card-separator {
            margin: 0.5rem auto;
            width: 60%;
            border: 0;
            border-top: 1px solid rgba(var(--bs-primary-rgb), 0.4);
        }
    </style>
    @endpush
@endonce