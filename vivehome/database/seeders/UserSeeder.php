<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Importar Hash
use App\Models\User; // Importar el modelo User

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear un Super Administrador
        User::create([
            'name' => 'Administrador ViveHome',
            'email' => 'admin@vivehome.cl', // Puedes cambiar este email
            'password' => Hash::make('password'), // Cambia 'password' por una contraseña segura
            'role' => 'admin',
            'email_verified_at' => now(), // Marcar como verificado
        ]);

        // Opcional: Crear algunos usuarios Asesores de prueba
        User::create([
            'name' => 'Asesor Uno',
            'email' => 'asesor1@vivehome.cl',
            'password' => Hash::make('password'),
            'role' => 'asesor',
            'email_verified_at' => now(),
        ]);
    }
}