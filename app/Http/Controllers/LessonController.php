<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLessonRequest;
use App\Http\Requests\UpdateLessonRequest;
use App\Models\Lesson;
use App\Models\Pupil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LessonController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Lesson::class, 'lesson');
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
    public function create(Pupil $pupil)
    {
        $pupilLessonCount = $pupil->lessons->count();
        return Inertia::render('Lesson/Create', [
            'pupil' => $pupil->load('user'),
            'name_suggestions' => ['Урок ' . ($pupilLessonCount + 1), 'Диагностика']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLessonRequest $request)
    {
        $dates = $request->validated()['start_dates'];
        foreach ($dates as $date) {
            $lesson = new Lesson();
            $lesson = $lesson->fill($request->validated());
            $lesson->start_at = Carbon::parse($date)->setTimeFromTimeString($request->validated()['start_time']);
            $lesson->save();
        }

        $request->session()->flash('flash.banner', 'Урок создан');
        $request->session()->flash('flash.bannerStyle', 'success');
        return to_route('lesson.show', $lesson->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Lesson $lesson)
    {
        $sortedLessons = $lesson->pupil->lessons
            ->sortBy([
                fn(Lesson $a, Lesson $b) => strtotime($a['start_at']) <=> strtotime($b['start_at']),
                fn(Lesson $a, Lesson $b) => $b['id'] <=> $a['id'],
            ])->values();
        $currentLessonIndex = $sortedLessons->where('id', $lesson->id)->keys()->first();
        return Inertia::render('Lesson/Show', [
            'previousLesson' => $sortedLessons->get($currentLessonIndex - 1),
            'nextLesson' => $sortedLessons->get($currentLessonIndex + 1),
            'pupilLessons' => $sortedLessons->values(),
            'lesson' => $lesson->load(['pupil.user', 'learningMaterials.tags'])
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
        $request->session()->flash('flash.banner', 'Информация об уроке обновлена');
        $request->session()->flash('flash.bannerStyle', 'success');
        return to_route('lesson.show', $lesson->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,  Lesson $lesson)
    {
        $request->session()->flash('flash.banner', 'Урок удален');
        $request->session()->flash('flash.bannerStyle', 'success');
        $lesson->delete();
        return to_route('main');
    }
}
