<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLearningMaterialRequest;
use App\Http\Requests\UpdateLearningMaterialRequest;
use App\Models\LearningMaterial;
use Inertia\Inertia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\Tag;

class LearningMaterialController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(LearningMaterial::class, 'learning_material');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLearningMaterialRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(LearningMaterial $learningMaterial)
    {
        $learningMaterialWithLoaded = $learningMaterial->load(['tags', 'media', 'lessons.pupil']);
        $learningMaterialWithLoaded->media->transform(function ($media) {
            return [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'name' => $media->name
            ];
        });
        return Inertia::render('LearningMaterial/Show', [
            'learning_material' => $learningMaterialWithLoaded,
            'usedForPupils' => $learningMaterial->lessons->pluck('pupil')->unique('id'),
            'tags' => Tag::getWithType('learning_material')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LearningMaterial $learningMaterial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLearningMaterialRequest $request, LearningMaterial $learningMaterial)
    {
        //delete unused media
        $learningMaterial->media
            ->reject(fn(Media $media) => in_array($media->id, $request->mediaIds))
            ->each(function ($media) {
                $media->delete();
            });
        //associate new media
        Media::whereIn('id', $request->mediaIds)
            ->get()
            ->each(function ($media) use ($learningMaterial) {
                $media->model_id = $learningMaterial->id;
                $media->model_type = LearningMaterial::class;
                $media->save();
            });
        $learningMaterial->update($request->validated());
        $learningMaterial->syncTags(collect($request->tags)->pluck('name')->toArray());
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LearningMaterial $learningMaterial)
    {
        //
    }
}
