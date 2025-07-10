<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Importar BelongsTo

class PropertyPhoto extends Model
{
    use HasFactory;

    protected $table = 'property_photos'; // Especificar nombre de tabla si no sigue convención exacta de Laravel

    protected $fillable = [
        'property_id',
        'file_path',
        'caption',
        'order',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Define la relación: una Foto de Propiedad pertenece a una Propiedad.
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}