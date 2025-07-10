<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str; // Para generar slugs

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info("--- Depurando PropertySeeder ---");
$allUsers = User::all();
if ($allUsers->isEmpty()) {
    $this->command->warn("PropertySeeder: No hay NINGÚN usuario en la tabla 'users' en este momento.");
} else {
    $this->command->info("PropertySeeder: Usuarios encontrados en la tabla 'users':");
    foreach ($allUsers as $u) {
        $this->command->line("  - ID: {$u->id}, Nombre: {$u->name}, Email: {$u->email}, Rol: {$u->role}");
    }
}
$asesorCount = User::where('role', 'asesor')->count();
$this->command->info("PropertySeeder: Conteo de usuarios con rol 'asesor': " . $asesorCount);
$this->command->info("--- Fin Depuración PropertySeeder ---");

        // Obtener un asesor y una categoría (o manejar el caso de que no existan)
        $asesor = User::where('role', 'asesor')->orderBy('id', 'asc')->first();
        $categoriaCasa = Category::where('slug', 'casa')->first(); // Busca por slug 'casa'
        $categoriaDepto = Category::where('slug', 'departamento')->first(); // Busca por slug 'departamento'

        if (!$asesor) {
            $this->command->error("No se encontró un usuario con rol 'asesor'. Crea uno antes de ejecutar el PropertySeeder o ajústalo.");
            return;
        }
        if (!$categoriaCasa || !$categoriaDepto) {
            $this->command->warn("Una o más categorías de prueba ('casa', 'departamento') no fueron encontradas. Algunas propiedades podrían no crearse o necesitarás ajustar el seeder.");
        }

        $properties = [
            [
                'user_id' => $asesor->id,
                'category_id' => $categoriaCasa ? $categoriaCasa->id : null,
                'title' => 'Casa Amplia con Jardín (Seeder)',
                'description' => 'Hermosa casa con 4 dormitorios, 3 baños y un gran jardín. Ideal para familias.',
                'operation_type' => 'Venta',
                'type_property' => 'Casa',
                'status' => 'Disponible',
                'price' => 220000000,
                'currency' => 'CLP',
                'address' => 'Los Cerezos 123',
                'commune' => 'La Serena',
                'city' => 'La Serena',
                'bedrooms' => 4,
                'bathrooms' => 3,
                'total_area_m2' => 600,
                'built_area_m2' => 250,
            ],
            [
                'user_id' => $asesor->id,
                'category_id' => $categoriaDepto ? $categoriaDepto->id : null,
                'title' => 'Departamento Moderno Céntrico (Seeder)',
                'description' => 'Moderno departamento de 2 dormitorios y 2 baños en el corazón de la ciudad.',
                'operation_type' => 'Arriendo',
                'type_property' => 'Departamento',
                'status' => 'Disponible',
                'price' => 650000,
                'currency' => 'CLP',
                'address' => 'Avenida Central 456',
                'commune' => 'Coquimbo',
                'city' => 'Coquimbo',
                'bedrooms' => 2,
                'bathrooms' => 2,
                'total_area_m2' => 90,
                'built_area_m2' => 80,
            ],
            // Puedes añadir más arrays de propiedades aquí
        ];

        foreach ($properties as $propertyData) {
            // Generar slug a partir del título
            $propertyData['slug'] = Str::slug($propertyData['title']);

            // Usar updateOrCreate para evitar duplicados si el seeder se ejecuta múltiples veces
            // y si tienes una forma única de identificar una propiedad (ej. slug y user_id)
            // O simplemente Property::create si estás seguro de que no habrá duplicados en ejecuciones repetidas
            // o si siempre haces migrate:fresh --seed.
            Property::create($propertyData);
        }

        $this->command->info('Tabla de Propiedades poblada con datos de prueba!');
    }
}