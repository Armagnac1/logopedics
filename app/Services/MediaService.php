<?php

namespace App\Services;

use App\Services\Abstracts\MediaServiceInterface;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaService implements MediaServiceInterface
{
    public function storeMedia(array $files): Collection
    {
        $storedMedia = collect();
        foreach ($files as $file) {
            $storedMedia[] = auth()->user()->addMedia($file)->toMediaCollection('learning_materials_files');
        }
        return $storedMedia;
    }

    public function showMedia(Media $media): Media
    {
        return $media;
    }

    public function deleteMedia(Media $media): void
    {
        $media->delete();
    }
}
