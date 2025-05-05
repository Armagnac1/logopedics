<?php

namespace App\Repositories\Contracts;

use App\Models\Tag;

interface TagRepositoryInterface
{
    public function getTagsForModel(string $modelClass);

    public function deleteTag(Tag $tag);
}
