<?php

namespace Database\Seeders;

use App\Models\LearningMaterial;
use App\Models\Lesson;
use App\Models\Pupil;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pupil::get()->each(function ($pupil) {
            $materials = LearningMaterial::get()->random(fn($items) => rand(0, 10));
            Lesson::factory(20)->hasAttached($materials)->for($pupil)->create();
        });
    }
}
