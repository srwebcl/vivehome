<?php

use App\Http\Controllers\Admin\AllPropertyController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomFieldDefinitionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Asesor\PropertyController as AsesorPropertyController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- INICIO: RUTAS PÚBLICAS --- 
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/propiedades', [PageController::class, 'properties'])->name('public.properties.index');
Route::get('/propiedades/{property:slug}', [PageController::class, 'showProperty'])->name('public.properties.show');
Route::get('/quienes-somos', [PageController::class, 'about'])->name('public.about');
Route::get('/contacto', [PageController::class, 'contact'])->name('public.contact');
// --- FIN: RUTAS PÚBLICAS ---


// --- Ruta del Dashboard Principal (Redirige según el rol) --- 
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    if ($user->role === 'asesor') {
        return redirect()->route('asesor.properties.index');
    }
    // Fallback por si acaso 
    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

// --- GRUPO DE RUTAS DEL SUPER-ADMINISTRADOR --- 
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('custom-field-definitions', CustomFieldDefinitionController::class)->names('custom_fields');
    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    
    // --- RUTAS PARA GESTIÓN DE PROPIEDADES POR ADMIN (CRUD COMPLETO) ---
    Route::get('all-properties', [AllPropertyController::class, 'index'])->name('all-properties.index');
    // INICIO: NUEVAS RUTAS AÑADIDAS
    Route::get('all-properties/create', [AllPropertyController::class, 'create'])->name('all-properties.create');
    Route::post('all-properties', [AllPropertyController::class, 'store'])->name('all-properties.store');
    Route::get('all-properties/{property}/edit', [AllPropertyController::class, 'edit'])->name('all-properties.edit');
    Route::put('all-properties/{property}', [AllPropertyController::class, 'update'])->name('all-properties.update');
    // FIN: NUEVAS RUTAS AÑADIDAS
    Route::get('all-properties/{property}', [AllPropertyController::class, 'show'])->name('all-properties.show');
    Route::patch('all-properties/{property}/status', [AllPropertyController::class, 'updateStatus'])->name('all-properties.updateStatus');
    Route::delete('all-properties/{property}', [AllPropertyController::class, 'destroy'])->name('all-properties.destroy');
});


// --- GRUPO DE RUTAS PARA ASESORES --- 
Route::middleware(['auth', 'verified', 'asesor'])->prefix('asesor')->name('asesor.')->group(function () {
    Route::resource('properties', AsesorPropertyController::class);
});

// --- GRUPO DE RUTAS DE PERFIL DE USUARIO (PARA TODOS) --- 
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- Rutas de autenticación de Breeze --- 
require __DIR__.'/auth.php';