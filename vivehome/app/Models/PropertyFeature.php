<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // Importar BelongsToMany

class PropertyFeature extends Model
{
    use HasFactory;

    protected $table = 'property_features';

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Define la relación: una Característica pertenece a muchas Propiedades (muchos-a-muchos).
     */
    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class, 'feature_property', 'property_feature_id', 'property_id')
                    ->withTimestamps();
    }
}