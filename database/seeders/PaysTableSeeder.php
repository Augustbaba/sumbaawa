<?php

namespace Database\Seeders;

use App\Models\Pays;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pays::create([
            'name' => 'Bénin',
            'slug' => Str::slug('Bénin'),
        ]);
        Pays::create([
            'name' => 'Belgique',
            'slug' => Str::slug('Belgique'),
        ]);
        Pays::create([
            'name' => 'Suisse',
            'slug' => Str::slug('Suisse'),
        ]);
        Pays::create([
            'name' => 'Canada',
            'slug' => Str::slug('Canada'),
        ]);
    }
}
