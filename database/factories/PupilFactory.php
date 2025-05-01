<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pupil>
 */
class PupilFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement(['male', 'female']);

        return [
            'user_id' => User::factory()->create()->assignRole('pupil')->id,
            'city_id' => $this->faker->numberBetween(1, 1000),
            'lesson_duration' => $this->faker->randomElement([30, 40, 50, 60]),
            'tutor_comments' => fake()->realText(200),
            'full_name' => fake()->firstName($gender).' '.fake()->lastName($gender),
        ];
    }
}
