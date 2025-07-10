<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{ 
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            // Definición de claves foráneas al final para mejor legibilidad
            $table->unsignedBigInteger('user_id'); // Asesor asociado
            $table->unsignedBigInteger('category_id'); // Categoría de la propiedad

            $table->string('title'); // Título descriptivo
            $table->string('slug')->unique(); // Slug único para URLs amigables
            $table->text('description'); // Descripción detallada
            $table->string('operation_type'); // Tipo de operación, ej: 'venta', 'arriendo'
            $table->decimal('price', 15, 2); // Precio, ej. 15 dígitos totales, 2 decimales
            $table->string('currency')->default('CLP'); // Moneda, ej: 'CLP', 'UF'

            $table->string('address')->nullable(); // Dirección
            $table->string('commune')->nullable(); // Comuna
            $table->string('city')->nullable(); // Ciudad
            $table->string('region')->nullable(); // Región
            $table->decimal('latitude', 10, 7)->nullable(); // Coordenada de latitud
            $table->decimal('longitude', 10, 7)->nullable(); // Coordenada de longitud

            // Nombres de columna para superficies. El Seeder debe usar estos mismos nombres.
            $table->decimal('total_area_m2', 8, 2)->nullable(); // Superficie total en m²
            $table->decimal('built_area_m2', 8, 2)->nullable(); // Superficie construida en m²

            $table->unsignedInteger('bedrooms')->nullable(); // Número de dormitorios
            $table->unsignedInteger('bathrooms')->nullable(); // Número de baños
            $table->unsignedInteger('parking_lots')->default(0)->nullable(); // Número de estacionamientos
            $table->unsignedInteger('storage_units')->default(0)->nullable(); // Número de bodegas

            $table->string('status')->default('disponible'); // Estado, ej: 'disponible', 'arrendada', 'vendida'
            $table->boolean('is_featured')->default(false); // Para destacar propiedades
            $table->timestamps(); // Campos created_at y updated_at

            // Definición de claves foráneas
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Si se borra un usuario, se borran sus propiedades. Considera 'restrict' o 'set null' si prefieres otro comportamiento.
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict'); // Previene borrar una categoría si tiene propiedades asociadas.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
}; 