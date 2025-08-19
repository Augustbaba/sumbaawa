<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $this->call([
            PaysTableSeeder::class,
            TypeTableSeeder::class,
            RoleTableSeeder::class,
            UserTableSeeder::class,
            CategoryTableSeeder::class,
            SousCategoryTableSeeder::class,
            ProduitTableSeeder::class,
            PartnerTableSeeder::class,
        ]);
    }
}
