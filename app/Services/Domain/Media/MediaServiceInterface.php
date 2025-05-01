<?php

namespace App\Services\Domain\Media;

use Illuminate\Support\Collection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

interface MediaServiceInterface
{
    public function storeMedia(array $files): Collection;

    public function showMedia(Media $media): Media;

    public function deleteMedia(Media $media): void;
}
