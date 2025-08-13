<?php

namespace Database\Seeders;

use App\Models\SousCategorie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SousCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SousCategorie::create([
            'label' => 'Fauteuils ergonomiques',
            'slug' => Str::slug('Fauteuils ergonomiques'),
            'categorie_id' => 1,
        ]);

        SousCategorie::create([
            'label' => 'Fauteuils de direction',
            'slug' => Str::slug('Fauteuils de direction'),
            'categorie_id' => 1,
        ]);

        SousCategorie::create([
            'label' => 'Fauteuils visiteurs',
            'slug' => Str::slug('Fauteuils visiteurs'),
            'categorie_id' => 1,
        ]);

        // Catégorie 2 : Chaises et autres assises
        SousCategorie::create([
            'label' => 'Chaises de réunion',
            'slug' => Str::slug('Chaises de réunion'),
            'categorie_id' => 2,
        ]);

        SousCategorie::create([
            'label' => 'Tabourets',
            'slug' => Str::slug('Tabourets'),
            'categorie_id' => 2,
        ]);

        SousCategorie::create([
            'label' => 'Poufs et bancs',
            'slug' => Str::slug('Poufs et bancs'),
            'categorie_id' => 2,
        ]);

        // Catégorie 3 : Bureaux et benchs
        SousCategorie::create([
            'label' => 'Bureaux individuels',
            'slug' => Str::slug('Bureaux individuels'),
            'categorie_id' => 3,
        ]);

        SousCategorie::create([
            'label' => 'Bureaux partagés (benchs)',
            'slug' => Str::slug('Bureaux partagés benchs'),
            'categorie_id' => 3,
        ]);

        SousCategorie::create([
            'label' => 'Bureaux réglables en hauteur',
            'slug' => Str::slug('Bureaux réglables en hauteur'),
            'categorie_id' => 3,
        ]);

        // Catégorie 4 : Tables
        SousCategorie::create([
            'label' => 'Tables de réunion',
            'slug' => Str::slug('Tables de réunion'),
            'categorie_id' => 4,
        ]);

        SousCategorie::create([
            'label' => 'Tables basses',
            'slug' => Str::slug('Tables basses'),
            'categorie_id' => 4,
        ]);

        SousCategorie::create([
            'label' => 'Tables pliantes',
            'slug' => Str::slug('Tables pliantes'),
            'categorie_id' => 4,
        ]);

        // Catégorie 5 : Cabines
        SousCategorie::create([
            'label' => 'Cabines acoustiques',
            'slug' => Str::slug('Cabines acoustiques'),
            'categorie_id' => 5,
        ]);

        SousCategorie::create([
            'label' => 'Cabines téléphoniques',
            'slug' => Str::slug('Cabines téléphoniques'),
            'categorie_id' => 5,
        ]);

        SousCategorie::create([
            'label' => 'Cabines de réunion',
            'slug' => Str::slug('Cabines de réunion'),
            'categorie_id' => 5,
        ]);

        // Catégorie 6 : Rangements et accessoires
        SousCategorie::create([
            'label' => 'Armoires',
            'slug' => Str::slug('Armoires'),
            'categorie_id' => 6,
        ]);

        SousCategorie::create([
            'label' => 'Étagères',
            'slug' => Str::slug('Étagères'),
            'categorie_id' => 6,
        ]);

        SousCategorie::create([
            'label' => 'Supports et organisateurs',
            'slug' => Str::slug('Supports et organisateurs'),
            'categorie_id' => 6,
        ]);

    }
}
