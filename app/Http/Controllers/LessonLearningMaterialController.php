<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lesson\StoreLessonLearningMaterialRequest;
use App\Models\Lesson;
use App\Repositories\Abstracts\LessonRepositoryInterface;
use Illuminate\Support\Facades\Gate;

class LessonLearningMaterialController extends Controller
{
    protected $lessonRepository;

    public function __construct(LessonRepositoryInterface $lessonRepository)
    {
        $this->lessonRepository = $lessonRepository;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLessonLearningMaterialRequest $request)
    {
        $lesson = $this->lessonRepository->find($request->lessonId);
        Gate::authorize('update', $lesson);
        $ids = collect($request->materials)->pluck('id');
        $this->lessonRepository->attachLearningMaterials($lesson, $ids);

        session()->flash('flash.banner', __('messages.model_attached', ['attachable' => __('models.learningMaterial'), 'model' => __('models.lesson')]));
        session()->flash('bannerStyle', 'success');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($lesson_learning_material)
    {
        $pivot = $this->lessonRepository->findPivotMaterialById($lesson_learning_material);
        Gate::authorize('delete', Lesson::find($pivot->lesson_id));
        $this->lessonRepository->detachLearningMaterial($lesson_learning_material);

        session()->flash('flash.banner', __('messages.model_detached', ['model' => __('models.lesson'), 'attachable' => __('models.learningMaterial')]));
        session()->flash('bannerStyle', 'success');
    }
}
