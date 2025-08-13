<?php

namespace Database\Seeders;

use App\Helpers\FrontHelper;
use App\Models\Partner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PartnerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Partner::create([
            'name' => 'WisaPay',
            'slug' => Str::slug('WisaPay1'),
            'image' => FrontHelper::getEnvFolder() .'partners/p1.png',
        ]);
        Partner::create([
            'name' => 'WisaPay',
            'slug' => Str::slug('WisaPay2'),
            'image' => FrontHelper::getEnvFolder() .'partners/p1.png',
        ]);
        Partner::create([
            'name' => 'WisaPay',
            'slug' => Str::slug('WisaPay3'),
            'image' => FrontHelper::getEnvFolder() .'partners/p1.png',
        ]);
        Partner::create([
            'name' => 'WisaPay',
            'slug' => Str::slug('WisaPay4'),
            'image' => FrontHelper::getEnvFolder() .'partners/p1.png',
        ]);
        Partner::create([
            'name' => 'WisaPay',
            'slug' => Str::slug('WisaPay5'),
            'image' => FrontHelper::getEnvFolder() .'partners/p1.png',
        ]);
    }
}
