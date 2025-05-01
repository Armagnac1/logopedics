<?php

namespace Database\Seeders;

use App\Models\LearningMaterial;
use App\Models\Lesson;
use App\Models\Pupil;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $learningMaterials = LearningMaterial::get();
        Pupil::get()->each(function ($pupil) use ($learningMaterials) {
            Lesson::factory(20)->state(new Sequence(
                fn (Sequence $sequence) => [
                    'pupil_id' => $pupil->id,
                ],
            ))->create()->each(function ($lesson) use ($learningMaterials) {
                $lesson->learningMaterials()->attach($learningMaterials->random(6));
            });
        });
    }
}
