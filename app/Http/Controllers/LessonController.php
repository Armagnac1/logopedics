<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lesson\StoreLessonRequest;
use App\Http\Requests\Lesson\UpdateLessonRequest;
use App\Models\Lesson;
use App\Models\Pupil;
use App\Repositories\Abstracts\LessonRepositoryInterface;
use Illuminate\Support\Str;
use Inertia\Inertia;

class LessonController extends Controller
{
    protected LessonRepositoryInterface $lessonRepository;

    public function __construct(LessonRepositoryInterface $lessonRepository)
    {
        $this->lessonRepository = $lessonRepository;
        $this->authorizeResource(Lesson::class, 'lesson');
    }

    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Pupil $pupil)
    {
        return Inertia::render('Lesson/Create', [
            'pupil' => $pupil->load('user'),
            'name_suggestions' => [Str::ucfirst(__('models.lesson')) . ' ' . ($pupil->lessons->count() + 1)] //TODO: add more suggestions later
        ]);
    }

    public function store(StoreLessonRequest $request)
    {
        $data = $request->validated();
        $lesson = $this->lessonRepository->create($data);
        $request->session()->flash('flash.banner', __('messages.model_created', ['model' => __('models.lesson')]));
        $request->session()->flash('flash.bannerStyle', 'success');
        return to_route('lesson.show', $lesson->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Lesson $lesson)
    {
        $result = $this->lessonRepository->getSortedLessonsWithIndex($lesson);
        $sortedLessons = $result['sortedLessons'];
        $currentLessonIndex = $result['currentLessonIndex'];

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

    public function update(UpdateLessonRequest $request, Lesson $lesson)
    {
        $data = $request->validated();
        $lesson = $this->lessonRepository->update($lesson, $data);
        session()->flash('flash.banner', __('messages.model_updated', ['model' => __('models.lesson')]));
        session()->flash('flash.bannerStyle', 'success');
        return to_route('lesson.show', $lesson->id);
    }


    public function destroy(Lesson $lesson)
    {
        $this->lessonRepository->delete($lesson);
        session()->flash('flash.banner', __('messages.model_deleted', ['model' => __('models.lesson')]));
        session()->flash('flash.bannerStyle', 'success');
        return to_route('home');
    }
}
