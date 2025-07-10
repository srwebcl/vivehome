<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate(
            ['key' => 'max_photos_per_property'],
            [
                'name' => 'Máximo de Fotos por Propiedad',
                'value' => '20', // Valor predeterminado
                'type' => 'integer',
                'group' => 'Multimedia',
                'description' => 'Número máximo de imágenes que se pueden subir para cada propiedad.'
            ]
        );

        Setting::updateOrCreate(
            ['key' => 'max_videos_per_property'],
            [
                'name' => 'Máximo de Videos por Propiedad',
                'value' => '2', // Valor predeterminado
                'type' => 'integer',
                'group' => 'Multimedia',
                'description' => 'Número máximo de enlaces de video (o subidas directas) permitidos por propiedad.'
            ]
        );

        Setting::updateOrCreate(
            ['key' => 'max_photo_upload_size_kb'],
            [
                'name' => 'Tamaño Máx. Foto (KB)',
                'value' => '2048', // Valor predeterminado (2MB)
                'type' => 'integer',
                'group' => 'Multimedia',
                'description' => 'Tamaño máximo permitido para cada archivo de foto individual, en Kilobytes (ej. 2048 para 2MB).'
            ]
        );

        Setting::updateOrCreate(
            ['key' => 'max_video_upload_size_kb'],
            [
                'name' => 'Tamaño Máx. Video Directo (KB)',
                'value' => '102400', // Valor predeterminado (100MB)
                'type' => 'integer',
                'group' => 'Multimedia',
                'description' => 'Tamaño máximo permitido para cada archivo de video subido directamente (si está habilitado), en Kilobytes.'
            ]
        );

        // Puedes añadir más configuraciones predeterminadas aquí si es necesario
    }
}
