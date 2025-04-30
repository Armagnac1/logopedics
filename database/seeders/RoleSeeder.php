<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Enums\RoleEnum;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define permissions
        $permissions = [
            'superadmin',
            'login as others',
            'create/change/view owned learning material',
            'create lesson',
            'update lesson',
            'create/change pupil'
        ];

        // Create permissions
        $permissionInstances = [];
        foreach ($permissions as $permission) {
            $permissionInstances[$permission] = Permission::create(['name' => $permission]);
        }

        // Define roles and their permissions
        $roles = [
            RoleEnum::SUPERADMIN->value => [
                'superadmin',
                'login as others',
                'create/change/view owned learning material',
                'create lesson',
                'update lesson',
                'create/change pupil'
            ],
            RoleEnum::ADMIN->value => [
                'login as others',
                'create/change/view owned learning material',
                'create lesson',
                'update lesson',
                'create/change pupil'
            ],
            RoleEnum::TUTOR->value => [
                'create/change/view owned learning material',
                'update lesson'
            ],
            RoleEnum::TUTOR_SELLER->value => [
                'create/change/view owned learning material',
                'create lesson',
                'update lesson',
                'create/change pupil'
            ],
            RoleEnum::PUPIL->value => []
        ];

        // Create roles and sync permissions
        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::create(['name' => $roleName]);
            $role->syncPermissions(array_map(fn($permission) => $permissionInstances[$permission], $rolePermissions));
        }
    }
}
