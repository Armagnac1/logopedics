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
                'name' => 'superadmin',
                'email' => 'crack7747@gmail.com',
                'password' => 'test',
                'role' => 'superadmin',
            ],
            [
                'name' => 'admin',
                'email' => 'admin@test.com',
                'password' => 'test',
                'role' => 'superadmin',
            ],
            [
                'name' => 'tutor',
                'email' => 'tutor@test.com',
                'password' => 'test',
                'role' => 'tutor',
            ],
            [
                'name' => 'tutor-seller',
                'email' => 'tutor-seller@test.com',
                'password' => 'test',
                'role' => 'tutor-seller',
            ],
            [
                'name' => 'pupil',
                'email' => 'pupil@test.com',
                'password' => 'test',
                'role' => 'pupil',
            ],
        ];

        foreach ($users as $userData) {
            $user = new User();
            $user->name = $userData['name'];
            $user->email = $userData['email'];
            $user->password = bcrypt($userData['password']);
            $user->assignRole($userData['role']);
            $user->save();
        }
    }
}
