<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLearningMaterialRequest;
use App\Http\Requests\UpdateLearningMaterialRequest;
use App\Models\LearningMaterial;
use Inertia\Inertia;
use Spatie\Tags\Tag;

class LearningMaterialController extends Controller
{
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
        return Inertia::render('LearningMaterial/Show', [
            'learning_material' => $learningMaterial->load(['tags']),
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
