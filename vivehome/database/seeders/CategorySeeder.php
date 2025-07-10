<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category; // Importar el modelo Category
use Illuminate\Support\Str; // Importar Str para generar slugs

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Casa',
            'Departamento',
            'Oficina',
            'Bodega',
            'Local Comercial',
            'Terreno',
        ];

        foreach ($categories as $categoryName) {
            Category::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName), // Genera un slug amigable, ej. "local-comercial"
            ]);
        }
    }
}