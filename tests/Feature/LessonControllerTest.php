<?php

namespace Tests\Feature;

use App\Models\Tutor;
use App\Models\User;
use App\Models\Pupil;
use App\Models\Lesson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LessonControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create and authenticate user
        $this->user = User::first();
        $this->actingAs($this->user);
        // Create pupil for user
        $tutor = Tutor::factory()->for($this->user)->create();
        $this->pupil = Pupil::factory()->create(['tutor_id' => $tutor->id]);
    }

    public function test_create_form_is_displayed()
    {
        $response = $this->get(route('lesson.create', $this->pupil));
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
        $page->component('Lesson/Create')
            ->has('pupil')
            ->has('name_suggestions')
        );
    }

    public function test_lesson_can_be_stored()
    {
        $this->withoutExceptionHandling();

        $data = [
            'pupil_id' => $this->pupil->id,
            'title' => 'Test Lesson',
            'start_time' => '10:00',
            'start_dates' => ['2024-06-01'],
            'duration' => 60,
        ];

        $response = $this->post(route('lesson.store'), $data);

        // Debug: dump validation/session errors if any
        if (session('errors')) {
            dump(session('errors')->all());
        }

        $this->assertDatabaseHas('lessons', [
            'pupil_id' => $this->pupil->id,
            'title' => 'Test Lesson',
            'duration' => 60,
        ]);
        $lesson = \App\Models\Lesson::where('title', 'Test Lesson')->first();
        $response->assertRedirect(route('lesson.show', $lesson->id));
    }




    public function test_lesson_can_be_shown()
    {
        $lesson = Lesson::factory()->for($this->pupil)->create();

        $response = $this->get(route('lesson.show', $lesson));
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
        $page->component('Lesson/Show')
            ->has('lesson')
            ->has('pupilLessons')
        );
    }

    public function test_lesson_can_be_updated()
    {
        $lesson = Lesson::factory()->for($this->pupil)->create([
            'title' => 'Old Name',
        ]);

        $data = [
            'title' => 'Updated Name',
            // add other required fields here
        ];

        $response = $this->put(route('lesson.update', $lesson), $data);

        $this->assertDatabaseHas('lessons', [
            'id' => $lesson->id,
            'title' => 'Updated Name',
        ]);
        $response->assertRedirect(route('lesson.show', $lesson->id));
    }

    public function test_lesson_can_be_deleted()
    {
        $lesson = Lesson::factory()->for($this->pupil)->create();
        $lessonId = $lesson->id; // Store ID before deletion

        $response = $this->delete(route('lesson.destroy', $lesson));

        $this->assertDatabaseMissing('lessons', [
            'id' => $lessonId,
        ]);
        $response->assertRedirect(route('home'));
    }
}
