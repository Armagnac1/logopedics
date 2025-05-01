<?php

namespace App\Http\Controllers;

use App\Http\Requests\Media\StoreMediaRequest;
use App\Services\Abstracts\MediaServiceInterface;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaController extends Controller
{
    protected $mediaService;

    public function __construct(MediaServiceInterface $mediaService)
    {
        $this->authorizeResource(Media::class, 'media');
        $this->mediaService = $mediaService;
    }

    public function store(StoreMediaRequest $request)
    {
        $storedMedia = $this->mediaService->storeMedia($request->file('files'));

        return response()->json(['files' => $storedMedia->transform(function ($media) {
            return [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'name' => $media->name,
            ];
        })]);
    }

    public function show(Media $media)
    {
        return $this->mediaService->showMedia($media);
    }

    public function destroy(Media $media)
    {
        $this->mediaService->deleteMedia($media);
    }
}
