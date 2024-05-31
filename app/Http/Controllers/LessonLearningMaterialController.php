<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLessonLearningMaterialRequest;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class LessonLearningMaterialController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Lesson::class, 'lesson_learning_material');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLessonLearningMaterialRequest $request)
    {
        Gate::authorize('update', $request->lessonId);
        $lesson = Lesson::find($request->lessonId);
        $ids = collect($request->materials)->pluck('id');
        $lesson->learningMaterials()->attach($ids);
        session()->flash('success', 'Учебный материал добавлен к уроку');
        session()->flash('bannerStyle', 'success');
        return back();
    }


    /**
     * Display the specified resource.
     */
    public function show(Lesson $lesson)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lesson $lesson)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $lesson_learning_material)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($lesson_learning_material)
    {
        $pivot = \DB::table('lesson_learning_materials')->where('id', $lesson_learning_material)->first();
        Gate::authorize('delete', Lesson::find($pivot->lesson_id));
        \DB::table('lesson_learning_materials')->where('id', $lesson_learning_material)->delete();

        session()->flash('success', 'Учебный материал убран из урока');
        session()->flash('bannerStyle', 'success');
    }
}
