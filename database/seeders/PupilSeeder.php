<?php

namespace Database\Seeders;

use App\Models\Pupil;
use App\Models\Tutor;
use Illuminate\Database\Seeder;

class PupilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tutorIds = range(1, 4);

        foreach ($tutorIds as $tutorId) {
            Pupil::factory(20)->for(Tutor::find($tutorId))->create();
        }
    }
}
