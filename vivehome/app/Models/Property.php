<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'description',
        'operation_type',
        'type_property',
        'price',
        'currency',
        'address',
        'commune',
        'city',
        'region',
        'latitude',
        'longitude',
        'total_area_m2',
        'built_area_m2',
        'bedrooms',
        'bathrooms',
        'parking_lots',
        'storage_units',
        'status',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'total_area_m2' => 'decimal:2',
        'built_area_m2' => 'decimal:2',
        'is_featured' => 'boolean',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'parking_lots' => 'integer',
        'storage_units' => 'integer',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Property $property) {
            if ($property->photos()->exists()) {
                foreach ($property->photos as $photo) {
                    // --- INICIO: CÓDIGO CORREGIDO ---
                    // Se cambió 'image_path' por 'file_path' para que coincida con la estructura de la BD.
                    if ($photo->file_path && Storage::disk('public')->exists($photo->file_path)) {
                        Storage::disk('public')->delete($photo->file_path);
                    }
                    // --- FIN: CÓDIGO CORREGIDO ---
                    $photo->delete();
                }
            }

            if ($property->videos()->exists()) {
                foreach ($property->videos as $video) {
                    // Asumimos que no hay videos locales por ahora, solo se borra el registro
                    $video->delete();
                }
            }

            if ($property->customFieldValues()->exists()) {
                $property->customFieldValues()->delete();
            }

            if ($property->features()->exists()) {
                $property->features()->detach();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(PropertyPhoto::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(PropertyVideo::class);
    }

    public function customFieldValues(): HasMany
    {
        return $this->hasMany(PropertyCustomFieldValue::class);
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(PropertyFeature::class, 'feature_property', 'property_id', 'property_feature_id')
                    ->withTimestamps();
    }
}