<?php

namespace Database\Seeders;

use App\Helpers\FrontHelper;
use App\Models\Categorie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Categorie::create([
            'label' => 'Fauteuils de bureau',
            'slug' => Str::slug('Fauteuils de bureau'),
            'image' => FrontHelper::getEnvFolder() .'categories/c1.jpg',
        ]);

        Categorie::create([
            'label' => 'Chaises et autres assises',
            'slug' => Str::slug('Chaises et autres assises'),
            'image' => FrontHelper::getEnvFolder() .'categories/c2.jpg',
        ]);

        Categorie::create([
            'label' => 'Bureaux et benchs',
            'slug' => Str::slug('Bureaux et benchs'),
            'image' => FrontHelper::getEnvFolder() .'categories/c3.jpg',
        ]);

        Categorie::create([
            'label' => 'Tables',
            'slug' => Str::slug('Tables'),
            'image' => FrontHelper::getEnvFolder() .'categories/c4.jpeg',
        ]);

        Categorie::create([
            'label' => 'Cabines',
            'slug' => Str::slug('Cabines'),
            'image' => FrontHelper::getEnvFolder() .'categories/c5.jpg',
        ]);

        Categorie::create([
            'label' => 'Rangements et accessoires',
            'slug' => Str::slug('angements et accessoires'),
            'image' => FrontHelper::getEnvFolder() .'categories/c6.jpg',
        ]);
    }
}
