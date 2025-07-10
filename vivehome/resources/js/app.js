// Importar el archivo bootstrap.js de Laravel (maneja CSRF, etc.)
import './bootstrap'; // Esto usualmente ya está

// Importar todos los componentes JavaScript de Bootstrap
import * as bootstrap from 'bootstrap';

// Opcional: Hacer Bootstrap disponible globalmente (si alguna vez lo necesitas desde la consola del navegador o scripts en línea)
window.bootstrap = bootstrap;

// Si Breeze instaló Alpine.js y quieres seguir usándolo para interactividad ligera:
// import Alpine from 'alpinejs';
// window.Alpine = Alpine;
// Alpine.start();