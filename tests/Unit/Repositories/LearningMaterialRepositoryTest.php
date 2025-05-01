<?php

namespace Tests\Unit\Repositories;

use App\Models\LearningMaterial;
use App\Models\Lesson;
use App\Models\Pupil;
use App\Models\Tag;
use App\Repositories\LearningMaterialRepository;
use App\Services\CrossDomain\Ai\AISuggestionsService;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tests\TestCase;

class LearningMaterialRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new LearningMaterialRepository();
    }

    public function test_get_for_index_with_ai()
    {
        $pupil = Pupil::factory()->create();
        $lesson = Lesson::factory()->create(['pupil_id' => $pupil->id, 'duration' => 60]);
        $materials = LearningMaterial::factory()->count(2)->create();
        $this->mock(AISuggestionsService::class, function ($mock) use ($lesson, $materials) {
            $mock->shouldReceive('getLearningMaterialSuggestions')
                ->with(\Mockery::on(fn ($l) => $l instanceof \App\Models\Lesson && $l->id === $lesson->id))
                ->andReturn($materials->pluck('id')->toArray());
        });

        $filters = [
            'ai' => true,
            'filters' => ['lessonId' => $lesson->id],
        ];

        $result = $this->repo->getForIndex($filters);
        $this->assertCount(2, $result->items());
    }

    public function test_get_for_index_with_search()
    {
        $material = LearningMaterial::factory()->create(['title' => 'UniqueTitle']);
        $filters = [
            'search' => 'UniqueTitle',
            'filters' => [
                'onlyNotUsed' => '0',
            ],
        ];
        $result = $this->repo->getForIndex($filters);
        $this->assertTrue($result->contains('id', $material->id));
    }

    public function test_get_for_index_with_only_not_used()
    {
        $pupil = Pupil::factory()->create();
        $lesson = Lesson::factory()->create(['pupil_id' => $pupil->id, 'duration' => 60]);
        $usedMaterial = LearningMaterial::factory()->create();
        $unusedMaterial = LearningMaterial::factory()->create();
        $lesson->learningMaterials()->attach($usedMaterial);

        $filters = [
            'search' => null,
            'filters' => [
                'onlyNotUsed' => '1',
                'lessonId' => $lesson->id,
            ],
        ];
        $result = $this->repo->getForIndex($filters);
        $this->assertTrue($result->contains('id', $unusedMaterial->id));
        $this->assertFalse($result->contains('id', $usedMaterial->id));
    }

    public function test_get_for_index_with_tags()
    {
        $tag = Tag::first();
        $materialWithTag = LearningMaterial::factory()->create();
        $materialWithTag->tags()->attach($tag);
        $materialWithoutTag = LearningMaterial::factory()->create();

        $filters = [
            'search' => null,
            'filters' => [
                'tags' => [['id' => $tag->id]],
                'onlyNotUsed' => '0',
            ],
        ];
        $result = $this->repo->getForIndex($filters);
        $this->assertTrue($result->contains('id', $materialWithTag->id));
        $this->assertFalse($result->contains('id', $materialWithoutTag->id));
    }

    public function test_get_by_id_returns_with_relations()
    {
        $material = LearningMaterial::factory()->create();
        $tag = Tag::first();
        $material->tags()->attach($tag);
        $media = $this->createMedia(['model_id' => $material->id, 'model_type' => LearningMaterial::class]);
        $pupil = Pupil::factory()->create();
        $lesson = Lesson::factory()->create(['pupil_id' => $pupil->id, 'duration' => 60]);
        $material->lessons()->attach($lesson);

        $result = $this->repo->getById($material->id);
        $this->assertEquals($material->id, $result->id);
        $this->assertTrue($result->relationLoaded('tags'));
        $this->assertTrue($result->relationLoaded('media'));
        $this->assertTrue($result->relationLoaded('lessons'));
    }

    public function test_create_material_with_tags_and_media()
    {
        $user = \App\Models\User::factory()->create();
        $this->be($user);

        $tag = Tag::first();
        $media = $this->createMedia(['model_type' => LearningMaterial::class]);
        $data = [
            'title' => 'Test',
            'tags' => [['id' => $tag->id]],
            'mediaIds' => [$media->id],
        ];
        $material = $this->repo->create($data);

        $this->assertDatabaseHas('learning_materials', ['id' => $material->id, 'creator_user_id' => $user->id]);
        $this->assertTrue($material->tags->contains('id', $tag->id));
        $this->assertDatabaseHas('media', ['id' => $media->id, 'model_id' => $material->id]);
    }

    public function test_update_material_tags_and_media()
    {
        $material = LearningMaterial::factory()->create();
        $oldTag = Tag::first();
        $material->tags()->attach($oldTag);
        $oldMedia = $this->createMedia(['model_id' => $material->id, 'model_type' => LearningMaterial::class]);
        $newTag = Tag::where('id', '!=', $oldTag->id)->first();
        $newMedia = $this->createMedia(['model_type' => LearningMaterial::class]);

        $data = [
            'title' => 'Updated',
            'tags' => [['id' => $newTag->id]],
            'mediaIds' => [$newMedia->id],
        ];
        $result = $this->repo->update($material, $data);

        $this->assertTrue($result);
        $this->assertTrue($material->fresh()->tags->contains('id', $newTag->id));
        $this->assertDatabaseHas('media', ['id' => $newMedia->id, 'model_id' => $material->id]);
        $this->assertDatabaseMissing('media', ['id' => $oldMedia->id]);
    }

    public function test_delete_material()
    {
        $material = LearningMaterial::factory()->create();
        $result = $this->repo->delete($material);
        $this->assertTrue($result);
        $this->assertDatabaseMissing('learning_materials', ['id' => $material->id]);
    }

    public function test_get_suggestions_for_lesson()
    {
        $pupil = Pupil::factory()->create();
        $lesson = Lesson::factory()->create(['pupil_id' => $pupil->id, 'duration' => 60]);
        $materials = LearningMaterial::factory()->count(2)->create();
        $this->mock(AISuggestionsService::class, function ($mock) use ($lesson, $materials) {
            $mock->shouldReceive('getLearningMaterialSuggestions')
                ->with(\Mockery::on(fn ($l) => $l instanceof \App\Models\Lesson && $l->id === $lesson->id))
                ->andReturn($materials->pluck('id')->toArray());
        });

        $result = $this->repo->getSuggestionsForLesson($lesson->id);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
        $this->assertCount(2, $result);
    }

    protected function createMedia(array $overrides = [])
    {
        $faker = Faker::create();

        $attributes = array_merge([
            'model_type' => $faker->randomElement([
                'App\Models\LearningMaterial',
                'App\Models\Lesson',
                'App\Models\Pupil',
            ]),
            'model_id' => $faker->numberBetween(1, 100),
            'uuid' => $faker->uuid,
            'collection_name' => $faker->word,
            'name' => $faker->word,
            'file_name' => $faker->unique()->lexify('file_????.jpg'),
            'mime_type' => $faker->mimeType,
            'disk' => 'public',
            'conversions_disk' => null,
            'size' => $faker->numberBetween(1000, 1000000),
            'manipulations' => [],
            'custom_properties' => [],
            'generated_conversions' => [],
            'responsive_images' => [],
            'order_column' => $faker->optional()->numberBetween(1, 100),
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides);

        return Media::create($attributes);
    }
}
