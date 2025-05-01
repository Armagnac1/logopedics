<?php

namespace Database\Seeders;

use App\Models\LearningMaterial;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Spatie\Tags\Tag;

class TestLearningMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        LearningMaterial::factory(500)->state(new Sequence(
            fn (Sequence $sequence) => ['tags' => [Tag::getWithType('learning_material')->random()]],
        ))->create();
    }
}
