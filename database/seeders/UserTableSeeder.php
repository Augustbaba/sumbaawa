<?php

namespace Database\Seeders;

use App\Helpers\FrontHelper;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::whereName('admin')->first();
        $dev = Role::whereName('dev')->first();

        // $user1 = User::create([
        //     'name'=> "Mahugnon AMELINA",
        //     'email'=> "contacts.apconsulting@gmail.com",
        //     'phone'=> "002290190191559",
        //     'status' => true,
        //     'password' => bcrypt('Admin@01'),
        //     'created_at' => now(),
        //     'updated_at' => now(),
        //     'avatar' => FrontHelper::getEnvFolder() .'images/avatars/user-avatar-placeholder.png'
        // ]);
        // $user1->assignRole('admin');

        $user2 = User::create([
            'name'=> "Augustin Tobi HOUNTONDJI",
            'email'=> "augustinhountondji82@gmail.com",
            'phone'=> "002290153647262",
            'status' => true,
            'password' => bcrypt('Dev2@APC'),
            'created_at' => now(),
            'updated_at' => now(),
            'avatar' => FrontHelper::getEnvFolder() .'images/avatars/user-avatar-placeholder.png'
        ]);
        $user2->assignRole('dev');

        $user4 = User::create([
            'name'=> "Abdel Nawal Adébissi IMOROU",
            //'email'=> "nawalimorou57@gmail.com",
            'email'=> "adebissiimorou@gmail.com",
            'phone'=> "002290160258045",
            'status' => true,
            'password' => bcrypt('Dev3@APC'),
            'created_at' => now(),
            'updated_at' => now(),
            'avatar' => FrontHelper::getEnvFolder() .'images/avatars/user-avatar-placeholder.png'
        ]);
        $user4->assignRole('dev');

        $user5 = User::create([
            'name'=> "Super Administrateur",
            'email'=> "nawalimorou57@gmail.com",
            // 'email'=> "admin@gmail.com",
            'phone'=> "003366600000000",
            'status' => true,
            'password' => bcrypt('Admin@02'),
            'created_at' => now(),
            'updated_at' => now(),
            'avatar' => FrontHelper::getEnvFolder() .'images/avatars/user-avatar-placeholder.png'
        ]);
        $user5->assignRole('admin');


        $user6 = User::create([
            'name'=> "Zoul MAMA",
            'email'=> "zoul85@gmail.com",
            'phone'=> "002290100",
            'status' => true,
            'password' => bcrypt('client@01'),
            'created_at' => now(),
            'updated_at' => now(),
            'avatar' => FrontHelper::getEnvFolder() .'images/avatars/user-avatar-placeholder.png'
        ]);
        $user6->assignRole('customer');

        $user7 = User::create([
            'name'=> "Bio BONI",
            'email'=> "bio7896@gmail.com",
            'phone'=> "00229100",
            'status' => true,
            'password' => bcrypt('client@02'),
            'created_at' => now(),
            'updated_at' => now(),
            'avatar' => FrontHelper::getEnvFolder() .'images/avatars/user-avatar-placeholder.png'
        ]);
        $user7->assignRole('customer');

        $user8 = User::create([
            'name'=> "Adé BABA",
            //'email'=> "nawalimorou57@gmail.com",
            'email'=> "bill56@gmail.com",
            'phone'=> "0022900000",
            'status' => true,
            'password' => bcrypt('client@03'),
            'created_at' => now(),
            'updated_at' => now(),
            'avatar' => FrontHelper::getEnvFolder() .'images/avatars/user-avatar-placeholder.png'
        ]);
        $user8->assignRole('customer');

        $user9 = User::create([
            'name'=> "Chancelle CHABI",
            'email'=> "chab@gmail.com",
            // 'email'=> "admin@gmail.com",
            'phone'=> "0033666008900000",
            'status' => true,
            'password' => bcrypt('client@04'),
            'created_at' => now(),
            'updated_at' => now(),
            'avatar' => FrontHelper::getEnvFolder() .'images/avatars/user-avatar-placeholder.png'
        ]);
        $user9->assignRole('customer');

    }
}
