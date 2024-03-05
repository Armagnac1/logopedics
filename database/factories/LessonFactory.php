<?php

namespace Database\Factories;

use App\Enums\LessonStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lesson>
 */
class LessonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $scheduled = fake()->randomElement([true, false]);
        $finished = $scheduled && fake()->randomElement([true, false]);
        $startTime = null;
        $endTime = null;
        $tutor_comments = null;
        $homework_comments = null;
        $status = LessonStatus::CREATED;
        if ($scheduled) {
            $status = LessonStatus::SCHEDULED;
            $startTime = fake()->dateTimeBetween('now', '+30 days');
            $endTime = fake()->dateTimeBetween($startTime, (clone $startTime)->modify('+1 hours'));
        }
        if ($finished) {
            $status = $scheduled ? LessonStatus::FINISHED : LessonStatus::CREATED;
            $tutor_comments = fake()->realText(200);
            $homework_comments = fake()->realText(200);
        }
        return [
            'title' => fake()->realText(40),
            'status' => $status,
            'start_at' => $startTime,
            'end_at' => $endTime,
            'tutor_comments' => $tutor_comments,
            'homework_comments' => $homework_comments,
        ];
    }
}
