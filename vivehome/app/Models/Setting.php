<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'settings'; // Laravel lo infiere, pero es bueno ser explícito

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'name',
        'value',
        'type',
        'group',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    // Podríamos añadir casts aquí más adelante si es necesario,
    // por ejemplo, para convertir 'value' a su tipo real basado en la columna 'type'.
    // Por ahora, lo manejaremos como string y haremos la conversión en el controlador o un helper.
    // protected $casts = [
    //     // Ejemplo: 'value' => 'integer', // Esto sería si 'value' siempre fuera integer
    // ];
}
