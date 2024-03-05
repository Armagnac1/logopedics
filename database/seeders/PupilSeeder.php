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
        Pupil::factory(20)->for(Tutor::find(1))->create();
    }
}
