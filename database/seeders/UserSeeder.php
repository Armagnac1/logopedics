<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Dmitrii',
                'email' => 'crack7747@gmail.com',
                'password' => 'qwe123rty',
                'profile_photo_path' => 'https://i.pravatar.cc/300?u=1',
                'role' => 'superadmin',
            ],
            [
                'name' => 'admin',
                'email' => 'admin@test.com',
                'password' => 'test',
                'profile_photo_path' => 'https://i.pravatar.cc/300?u=2',
                'role' => 'superadmin',
            ],
            [
                'name' => 'tutor',
                'email' => 'tutor@test.com',
                'password' => 'test',
                'profile_photo_path' => 'https://i.pravatar.cc/300?u=3',
                'role' => 'tutor',
            ],
            [
                'name' => 'tutor-seller',
                'email' => 'tutor-seller@test.com',
                'password' => 'test',
                'profile_photo_path' => 'https://i.pravatar.cc/300?u=4',
                'role' => 'tutor-seller',
            ],
            [
                'name' => 'pupil',
                'email' => 'pupil@test.com',
                'password' => 'test',
                'profile_photo_path' => 'https://i.pravatar.cc/300?u=5',
                'role' => 'pupil',
            ],
        ];

        foreach ($users as $userData) {
            $user = new User();
            $user->name = $userData['name'];
            $user->email = $userData['email'];
            $user->password = bcrypt($userData['password']);
            $user->profile_photo_path = $userData['profile_photo_path'];
            $user->assignRole($userData['role']);
            $user->save();
        }
    }
}
