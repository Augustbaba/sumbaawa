<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Type::create([
            'label' => 'Livraison par bateau',
            'slug' => Str::slug('Livraison par bateau'),
        ]);
        Type::create([
            'label' => 'Livraison par avion',
            'slug' => Str::slug('Livraison par avion'),
        ]);
    }
}
