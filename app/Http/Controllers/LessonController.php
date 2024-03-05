<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLessonRequest;
use App\Http\Requests\UpdateLessonRequest;
use App\Models\Lesson;
use Inertia\Inertia;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Lesson/Index', [
            'lessons' => Lesson::query()
                ->with('pupil')
                ->paginate(20)
                ->through(function ($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'start_at' => $item->start_at,
                        'pupil' => $item->pupil
                    ];
                })

        ]);
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
    public function store(StoreLessonRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Lesson $lesson)
    {
        return Inertia::render('Lesson/Show', [
            'pupilLessons' => $lesson->pupil->lessons->sortBy('start_at')->values(),
            'lesson' => $lesson->load(['pupil', 'learningMaterials.tags'])
        ]);
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
    public function update(UpdateLessonRequest $request, Lesson $lesson)
    {
        $lesson->update($request->validated());
        return to_route('lesson.show', $lesson->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson)
    {
        //
    }
}
