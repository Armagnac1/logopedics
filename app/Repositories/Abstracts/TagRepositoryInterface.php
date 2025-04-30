<?php

namespace App\Repositories\Abstracts;

use App\Models\Tag;

interface TagRepositoryInterface
{
    public function getTagsForModel(string $modelClass);

    public function deleteTag(Tag $tag);
}
