<?php

namespace Tests\Unit\Repositories;

use App\Models\City;
use App\Models\Lesson;
use App\Models\Pupil;
use App\Models\Tag;
use App\Models\Tutor;
use App\Models\User;
use App\Repositories\PupilRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PupilRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new PupilRepository();
    }

    public function test_get_for_index_returns_pupils_with_upcoming_lessons_and_search()
    {
        $user = User::factory()->create();
        $tutor = Tutor::factory()->create(['user_id' => $user->id]);
        $this->be($user);

        $pupil1 = Pupil::factory()->create(['tutor_id' => $tutor->id, 'full_name' => 'Alice']);
        $pupil2 = Pupil::factory()->create(['tutor_id' => $tutor->id, 'full_name' => 'Bob']);
        $lesson1 = Lesson::factory()->create(['pupil_id' => $pupil1->id, 'start_at' => now()->addDay(), 'status' => \App\Enums\LessonStatus::SCHEDULED]);
        $lesson2 = Lesson::factory()->create(['pupil_id' => $pupil2->id, 'start_at' => now()->addDays(2), 'status' => \App\Enums\LessonStatus::SCHEDULED]);

        $result = $this->repo->getForIndex(null);
        $this->assertTrue($result->contains('id', $pupil1->id));
        $this->assertTrue($result->contains('id', $pupil2->id));
        $this->assertEquals($pupil1->id, $result->items()[0]->id);

        $resultSearch = $this->repo->getForIndex('Alice');
        $this->assertTrue($resultSearch->contains('id', $pupil1->id));
        $this->assertFalse($resultSearch->contains('id', $pupil2->id));
    }

    public function test_get_pupil_with_relations_loads_all_and_sorts_lessons()
    {
        $city = City::first();
        $tag = Tag::first();
        $pupil = Pupil::factory()->create(['city_id' => $city->id]);
        $pupil->tags()->attach($tag);

        $lesson1 = Lesson::factory()->create(['pupil_id' => $pupil->id, 'start_at' => now()->addDays(2)]);
        $lesson2 = Lesson::factory()->create(['pupil_id' => $pupil->id, 'start_at' => now()->addDay()]);
        $lesson3 = Lesson::factory()->create(['pupil_id' => $pupil->id, 'start_at' => now()->addDays(5)]);

        $pupil->refresh();
        $pupil = $this->repo->getPupilWithRelations($pupil);

        $this->assertTrue($pupil->relationLoaded('tags'));
        $this->assertTrue($pupil->relationLoaded('lessons'));
        $this->assertTrue($pupil->relationLoaded('city'));

        // Sort lessons by start_at ascending for assertion
        $sortedLessonIds = collect([$lesson1, $lesson2, $lesson3])
            ->sortBy(fn($l) => $l->start_at)
            ->pluck('id')
            ->values();

        $actualLessonIds = $pupil->lessons->pluck('id')->values();

        $this->assertEquals($sortedLessonIds, $actualLessonIds);
    }

    public function test_create_pupil_sets_tutor_id()
    {
        $user = User::factory()->create();
        $tutor = Tutor::factory()->create(['user_id' => $user->id]);
        $this->be($user);

        $data = ['full_name' => 'Test'];
        $pupil = $this->repo->create($data);

        $this->assertDatabaseHas('pupils', ['id' => $pupil->id, 'tutor_id' => $tutor->id]);
    }

    public function test_update_pupil_updates_fields()
    {
        $pupil = Pupil::factory()->create(['full_name' => 'Old']);
        $data = ['full_name' => 'New'];
        $updated = $this->repo->update($pupil, $data);

        $this->assertEquals('New', $updated->full_name);
        $this->assertDatabaseHas('pupils', ['id' => $pupil->id, 'full_name' => 'New']);
    }

    public function test_delete_pupil_removes_from_database()
    {
        $pupil = Pupil::factory()->create();
        $this->repo->delete($pupil);
        $this->assertDatabaseMissing('pupils', ['id' => $pupil->id]);
    }
}
