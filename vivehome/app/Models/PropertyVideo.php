<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Importar BelongsTo

class PropertyVideo extends Model
{
    use HasFactory;

    protected $table = 'property_videos';

    protected $fillable = [
        'property_id',
        'video_url',
        'platform',
        'caption',
    ];

    /**
     * Define la relación: un Video de Propiedad pertenece a una Propiedad.
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}