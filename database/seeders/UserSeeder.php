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
        $user = new User();
        $user->name = 'Dmitrii';
        $user->email = 'crack7747@gmail.com';
        $user->password = bcrypt('qwe123rty');
        $user->profile_photo_path = 'https://i.pravatar.cc/300?u=1';
        $user->assignRole('superadmin');
        $user->save();

        $user = new User();
        $user->name = 'admin';
        $user->email = 'admin@test.com';
        $user->password = bcrypt('test');
        $user->profile_photo_path = 'https://i.pravatar.cc/300?u=2';
        $user->assignRole('superadmin');
        $user->save();

        $user = new User();
        $user->name = 'tutor';
        $user->email = 'tutor@test.com';
        $user->password = bcrypt('test');
        $user->profile_photo_path = 'https://i.pravatar.cc/300?u=3';
        $user->assignRole('tutor');
        $user->save();

        $user = new User();
        $user->name = 'tutor-seller';
        $user->email = 'tutor-seller@test.com';
        $user->password = bcrypt('test');
        $user->profile_photo_path = 'https://i.pravatar.cc/300?u=4';
        $user->assignRole('tutor-seller');
        $user->save();

        $user = new User();
        $user->name = 'pupil';
        $user->email = 'pupil@test.com';
        $user->password = bcrypt('test');
        $user->profile_photo_path = 'https://i.pravatar.cc/300?u=5';
        $user->assignRole('pupil');
        $user->save();
    }
}
