@extends('layouts.admin')

@section('title', 'Dashboard Administrador - Vive Home')

@section('header')
    {{ __('Dashboard Principal') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>¡Bienvenido al Panel de Administración!</h4>
        </div>
        <div class="card-body">
            <p class="card-text">
                Hola, <strong>{{ Auth::user()->name }}</strong>. Desde aquí podrás gestionar los diferentes aspectos de la plataforma Vive Home.
            </p>
            <p class="card-text">
                Utiliza la barra de navegación superior para acceder a las diferentes secciones.
            </p>
            {{-- Aquí podrías añadir algunos widgets o accesos directos en el futuro --}}
        </div>
    </div>
@endsection