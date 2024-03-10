<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Dmitrii',
            'email' => 'crack7747@gmail.com',
            'password' => bcrypt('qwe123rty'),
            'profile_photo_path' => 'https://i.pravatar.cc/300?u=1'
        ]);

        DB::table('users')->insert([
            'name' => 'user2',
            'email' => 'user2@email.com',
            'password' => bcrypt('password'),
            'profile_photo_path' => 'https://i.pravatar.cc/300?u=2'
        ]);
    }
}
