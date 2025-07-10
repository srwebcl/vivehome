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
    Schema::table('properties', function (Blueprint $table) {
        // Añadimos la nueva columna 'type_operation'
        // ->nullable() permite que las filas existentes que no tengan este dato no causen error.
        // ->after('status') es opcional, coloca la columna después de la columna 'status'.
        // Puedes cambiar 'status' por el nombre de otra columna existente en tu tabla 'properties'
        // o simplemente omitir ->after(...) si la posición no te importa.
        $table->string('type_operation')->nullable()->after('status');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('properties', function (Blueprint $table) {
        // Esto elimina la columna si alguna vez necesitas revertir esta migración
        $table->dropColumn('type_operation');
    });
}
};
