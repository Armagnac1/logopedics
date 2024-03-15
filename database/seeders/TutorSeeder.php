<?php

namespace Database\Seeders;

use App\Models\Tutor;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TutorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tutor::factory()->for(User::find(1))->create();
        Tutor::factory()->for(User::find(2))->create();
        Tutor::factory()->for(User::find(3))->create();
        Tutor::factory()->for(User::find(4))->create();
    }
}
