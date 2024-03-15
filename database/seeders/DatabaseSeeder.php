<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\LearningMaterial;
use App\Models\Lesson;
use App\Models\Pupil;
use App\Models\Tutor;
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

        /*Pupil::factory(500)
            ->has(Lesson::factory()->count(10)->hasAttached(LearningMaterial::factory()->count(2)), 'lessons')
            ->has(Tutor::factory()->create())
            ->create();*/

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
