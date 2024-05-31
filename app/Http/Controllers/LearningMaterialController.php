<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLearningMaterialRequest;
use App\Http\Requests\UpdateLearningMaterialRequest;
use App\Models\LearningMaterial;
use App\Models\Lesson;
use Illuminate\Http\Request;
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
    public function index(Request $request)
    {

        if (!$request->inertia() && $request->expectsJson()) {
            $searchInput = $request->input('search');
            $filters = $request->input('filters');


            $learningMaterials = LearningMaterial::search($searchInput)->query(function ($builder) use ($filters) {
                $builder->with(['tags', 'media'])
                    ->when($filters['onlyNotUsed'] === '1', function ($query) use ($filters) {

                        $lesson = Lesson::find($filters['lessonId']);
                        $usedLearningMaterialsIds = $lesson->pupil->lessons->pluck('learningMaterials')->flatten()->pluck('id')->toArray();
                        $query->whereNotIn('id', $usedLearningMaterialsIds);
                    })->when(isset($filters['tags']) && count($filters['tags']) > 0, function ($query) use ($filters) {
                        $ids = collect($filters['tags'])->pluck('id');
                        $query->whereHas('tags', function ($query) use ($ids) {
                            $query->whereIn('id', $ids);
                        }, '=', count($ids));
                    });
            })->simplePaginate(10);

            return response()->json([
                'learning_materials' => $learningMaterials,
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('LearningMaterial/CreateShow', [
            'tags' => Tag::whereModel(LearningMaterial::class)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLearningMaterialRequest $request)
    {
        $learningMaterial = new LearningMaterial($request->validated());
        $learningMaterial->creator_user_id = auth()->id();
        $learningMaterial->save();
        $learningMaterial->syncTags(collect($request->tags)->pluck('name')->toArray());
        Media::whereIn('id', $request->mediaIds)
            ->get()
            ->each(function ($media) use ($learningMaterial) {
                $media->model_id = $learningMaterial->id;
                $media->model_type = LearningMaterial::class;
                $media->save();
            });
        $request->session()->flash('flash.banner', 'Учебный материал создан');
        $request->session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('learning_material.show', $learningMaterial);
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
        return Inertia::render('LearningMaterial/CreateShow', [
            'learning_material' => $learningMaterialWithLoaded,
            'usedForPupils' => $learningMaterial->lessons->pluck('pupil')->unique('id')->values(),
            'tags' => Tag::whereModel(LearningMaterial::class)->get(['id', 'name'])
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
        $request->session()->flash('flash.banner', 'Информация об учебном материале обновлена');
        $request->session()->flash('flash.bannerStyle', 'success');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, LearningMaterial $learningMaterial)
    {
        $learningMaterial->delete();
        $request->session()->flash('flash.banner', 'Материал удален');
        $request->session()->flash('flash.bannerStyle', 'success');
        return to_route('home');
    }
}
