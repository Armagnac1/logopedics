<?php

namespace App\Repositories\Eloquent;

use App\Models\Tag;
use App\Repositories\Contracts\TagRepositoryInterface;

class TagRepository implements TagRepositoryInterface
{
    public function getTagsForModel(string $modelClass)
    {
        return Tag::whereModel($modelClass)->get(['id', 'name']);
    }

    public function deleteTag(Tag $tag)
    {
        $tag->delete();
    }
}
