<?php

namespace Tests\Unit\Repositories;

use App\Models\Tag;
use App\Repositories\TagRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected TagRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new TagRepository();
    }

    public function test_get_tags_for_model_returns_only_matching_tags()
    {
        Tag::create(['model' => 'Post', 'name' => 'Laravel']);
        Tag::create(['model' => 'User', 'name' => 'PHP']);
        Tag::create(['model' => 'Post', 'name' => 'Testing']);

        $tags = $this->repository->getTagsForModel('Post');

        $this->assertCount(2, $tags);
        $this->assertEqualsCanonicalizing(
            ['Laravel', 'Testing'],
            $tags->pluck('name')->all()
        );
    }

    public function test_delete_tag_removes_tag_from_database()
    {
        $tag = Tag::create(['model' => 'Post', 'name' => 'DeleteMe']);

        $this->repository->deleteTag($tag);

        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    }
}
