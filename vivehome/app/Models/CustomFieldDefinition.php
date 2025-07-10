<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Importar HasMany

class CustomFieldDefinition extends Model
{
    use HasFactory;

    protected $table = 'custom_field_definitions';

    protected $fillable = [
        'name',
        'slug',
        'type',
        'options',
        'is_filterable',
    ];

    /**
     * Los atributos que deben ser convertidos.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array', // Convertir el JSON de opciones a un array PHP
        'is_filterable' => 'boolean',
    ];

    /**
     * Define la relación: una Definición de Campo Personalizado tiene muchos Valores.
     */
    public function values(): HasMany
    {
        return $this->hasMany(PropertyCustomFieldValue::class);
    }
}