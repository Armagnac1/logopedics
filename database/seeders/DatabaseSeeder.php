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
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $this->call([
            CitySeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            TagsSeeder::class,
            TestLearningMaterialSeeder::class,
            TutorSeeder::class,
            PupilSeeder::class,
            LessonSeeder::class,
        ]);
    }
}
