<?php

namespace Database\Seeders;

use App\Models\Tutor;
use App\Models\User;
use Illuminate\Database\Seeder;

class TutorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the range of user IDs
        $userIds = range(1, 4);

        // Loop through each user ID and create a tutor
        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if ($user) {
                Tutor::factory()->for($user)->create();
            }
        }
    }
}
