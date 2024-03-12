<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'superadmin']);
        $loginasothers = Permission::create(['name' => 'login as others']);
        $permissionMaterial = Permission::create(['name' => 'create/change/view owned learning material']);
        $permissionCreateLesson = Permission::create(['name' => 'create lesson']);
        $permissionUpdateLesson = Permission::create(['name' => 'update lesson']);
        $permissionPupil = Permission::create(['name' => 'create/change pupil']);

        Role::create(['name' => 'superadmin'])->syncPermissions([
            $permissionMaterial,
            $permissionCreateLesson,
            $permissionUpdateLesson,
            $permissionPupil,
            $loginasothers
        ]);

        Role::create(['name' => 'admin'])->syncPermissions([
            $permissionMaterial,
            $permissionCreateLesson,
            $permissionUpdateLesson,
            $permissionPupil,
            $loginasothers
        ]);
        Role::create(['name' => 'tutor'])->syncPermissions([
            $permissionMaterial,
            $permissionUpdateLesson
        ]);
        Role::create(['name' => 'tutor-seller'])->syncPermissions([
            $permissionMaterial,
            $permissionCreateLesson,
            $permissionUpdateLesson,
            $permissionPupil
        ]);
        Role::create(['name' => 'pupil']);

    }
}
