<?php

namespace Tests\Unit\Repositories;

use App\Models\Lesson;
use App\Models\Pupil;
use App\Models\Tutor;
use App\Models\User;
use App\Repositories\CalendarRepository;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class CalendarRepositoryTest extends TestCase
{
    public function test_generate_calendar_returns_valid_ical_response()
    {
        // Arrange
        $tutorId = 1;

        // Create a User mock with a non-null full_name
        $pupil = new User();
        $pupil->full_name = 'John Doe';

        // Create a Lesson mock with required properties
        $lesson = new Lesson();
        $lesson->start_at = '2024-06-01 10:00:00';
        $lesson->duration = 60;
        $lesson->title = 'Math Lesson';
        $lesson->setRelation('pupil', $pupil);

        $repo = Mockery::mock(CalendarRepository::class)->makePartial();
        $repo->shouldAllowMockingProtectedMethods();
        $repo->shouldReceive('getLessonsForTutor')
            ->with($tutorId)
            ->andReturn(collect([$lesson]));

        // Act
        $response = $repo->generateCalendar($tutorId);

        // Assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('text/calendar', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('John Doe', $response->getContent());
        $this->assertStringContainsString('Math Lesson', $response->getContent());
        $this->assertStringContainsString('BEGIN:VCALENDAR', $response->getContent());
    }

    public function test_get_lessons_for_tutor_returns_correct_lessons()
    {
        // Arrange
        $user = User::first();
        $tutor = Tutor::factory()->for($user)->create();
        $pupil1 = Pupil::factory()->create(['tutor_id' => $tutor->id]);
        $pupil2 = Pupil::factory()->create(['tutor_id' => $tutor->id]);
        $otherPupil = Pupil::factory()->create(); // Not assigned to tutor

        $lesson1 = Lesson::factory()->create(['pupil_id' => $pupil1->id]);
        $lesson2 = Lesson::factory()->create(['pupil_id' => $pupil2->id]);
        $lesson3 = Lesson::factory()->create(['pupil_id' => $otherPupil->id]);

        $repo = new CalendarRepository();

        // Act
        $lessons = $repo->getLessonsForTutor($tutor->id);

        // Assert
        $this->assertCount(2, $lessons);
        $lessonPupilIds = $lessons->pluck('pupil_id')->all();
        $this->assertContains($pupil1->id, $lessonPupilIds);
        $this->assertContains($pupil2->id, $lessonPupilIds);
        $this->assertNotContains($otherPupil->id, $lessonPupilIds);

        // Check relationships are loaded
        foreach ($lessons as $lesson) {
            $this->assertTrue($lesson->relationLoaded('pupil'));
            $this->assertTrue($lesson->pupil->relationLoaded('tags'));
        }
    }
}
