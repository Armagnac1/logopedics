<?php

namespace Tests\Unit\Repositories;

use App\Models\LearningMaterial;
use App\Models\Lesson;
use App\Models\Pupil;
use App\Repositories\LessonRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LessonRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new LessonRepository();
    }

    public function test_get_all_returns_all_lessons()
    {
        $pupil = Pupil::factory()->create();
        Lesson::factory()->count(3)->create(['pupil_id' => $pupil->id, 'duration' => 60]);
        $lessons = $this->repo->getAll();
        $this->assertCount(3, $lessons);
    }

    public function test_create_creates_lessons_with_multiple_dates()
    {
        $pupil = Pupil::factory()->create();
        $data = [
            'pupil_id' => $pupil->id,
            'start_dates' => ['2024-06-01', '2024-06-02'],
            'start_time' => '10:00:00',
            'title' => 'Math',
            'duration' => 60,
        ];
        $lesson = $this->repo->create($data);
        $this->assertDatabaseCount('lessons', 2);
        $this->assertEquals('2024-06-02 10:00:00', $lesson->start_at->format('Y-m-d H:i:s'));
    }

    public function test_get_sorted_lessons_with_index()
    {
        $pupil = Pupil::factory()->create();
        $lesson1 = Lesson::factory()->create(['pupil_id' => $pupil->id, 'start_at' => '2024-06-01 09:00:00', 'duration' => 60]);
        $lesson2 = Lesson::factory()->create(['pupil_id' => $pupil->id, 'start_at' => '2024-06-01 10:00:00', 'duration' => 60]);
        $lesson3 = Lesson::factory()->create(['pupil_id' => $pupil->id, 'start_at' => '2024-06-01 08:00:00', 'duration' => 60]);
        $result = $this->repo->getSortedLessonsWithIndex($lesson2);
        $this->assertEquals([$lesson3->id, $lesson1->id, $lesson2->id], $result['sortedLessons']->pluck('id')->toArray());
        $this->assertEquals(2, $result['currentLessonIndex']);
    }

    public function test_find_returns_lesson()
    {
        $pupil = Pupil::factory()->create();
        $lesson = Lesson::factory()->create(['pupil_id' => $pupil->id, 'duration' => 60]);
        $found = $this->repo->find($lesson->id);
        $this->assertEquals($lesson->id, $found->id);
    }

    public function test_update_updates_lesson()
    {
        $pupil = Pupil::factory()->create();
        $lesson = Lesson::factory()->create(['pupil_id' => $pupil->id, 'title' => 'Old', 'duration' => 60]);
        $this->repo->update($lesson, ['title' => 'New']);
        $this->assertEquals('New', $lesson->fresh()->title);
    }

    public function test_delete_removes_lesson()
    {
        $pupil = Pupil::factory()->create();
        $lesson = Lesson::factory()->create(['pupil_id' => $pupil->id, 'duration' => 60]);
        $this->repo->delete($lesson);
        $this->assertDatabaseMissing('lessons', ['id' => $lesson->id]);
    }

    public function test_attach_learning_materials()
    {
        $pupil = Pupil::factory()->create();
        $lesson = Lesson::factory()->create(['pupil_id' => $pupil->id, 'duration' => 60]);
        $materials = LearningMaterial::factory()->count(2)->create();
        $this->repo->attachLearningMaterials($lesson, $materials->pluck('id'));
        $this->assertCount(2, $lesson->learningMaterials);
    }

    public function test_find_pivot_material_by_id()
    {
        $pupil = Pupil::factory()->create();
        $lesson = Lesson::factory()->create(['pupil_id' => $pupil->id, 'duration' => 60]);
        $material = LearningMaterial::factory()->create();
        $lesson->learningMaterials()->attach($material->id);
        $pivot = DB::table('lesson_learning_materials')->first();
        $found = $this->repo->findPivotMaterialById($pivot->id);
        $this->assertEquals($pivot->id, $found->id);
    }

    public function test_detach_learning_material()
    {
        $pupil = Pupil::factory()->create();
        $lesson = Lesson::factory()->create(['pupil_id' => $pupil->id, 'duration' => 60]);
        $material = LearningMaterial::factory()->create();
        $lesson->learningMaterials()->attach($material->id);
        $pivot = DB::table('lesson_learning_materials')->first();
        $this->repo->detachLearningMaterial($pivot->id);
        $this->assertDatabaseMissing('lesson_learning_materials', ['id' => $pivot->id]);
    }
}
