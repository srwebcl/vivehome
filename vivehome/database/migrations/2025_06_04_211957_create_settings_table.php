<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Identificador único para la configuración (ej. 'max_photos_per_property')
            $table->string('name');          // Nombre legible para mostrar en el panel (ej. 'Máximo de Fotos por Propiedad')
            $table->text('value')->nullable(); // Valor de la configuración (puede ser string, número, JSON, etc.)
            $table->string('type')->default('string'); // Tipo de valor: string, integer, boolean, text, json
            $table->string('group')->default('General'); // Grupo para organizar las configuraciones en el panel (ej. 'Multimedia', 'General')
            $table->text('description')->nullable(); // Descripción de la configuración para el admin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
