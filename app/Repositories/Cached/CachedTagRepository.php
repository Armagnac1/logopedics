<?php

namespace App\Repositories\Cached;

use App\Models\Tag;
use App\Repositories\Contracts\TagRepositoryInterface;
use App\Repositories\Eloquent\TagRepository;
use Illuminate\Support\Facades\Cache;

class CachedTagRepository implements TagRepositoryInterface
{
    protected TagRepositoryInterface $repo;

    public function __construct(TagRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getTagsForModel(string $modelClass)
    {
        $cacheKey = 'tags.model.' . str_replace('\\', '_', $modelClass);

        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($modelClass) {
            return $this->repo->getTagsForModel($modelClass);
        });
    }

    public function deleteTag(Tag $tag)
    {
        // Invalidate cache for all models when a tag is deleted (or implement more granular logic)
        Cache::tags(['tags'])->flush();

        $this->repo->deleteTag($tag);
    }
}
