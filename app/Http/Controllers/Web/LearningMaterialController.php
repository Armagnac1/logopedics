<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\LearningMaterial\IndexLearningMaterialRequest;
use App\Http\Requests\LearningMaterial\StoreLearningMaterialRequest;
use App\Http\Requests\LearningMaterial\UpdateLearningMaterialRequest;
use App\Models\LearningMaterial;
use App\Repositories\Contracts\LearningMaterialRepositoryInterface;
use App\Repositories\Contracts\TagRepositoryInterface;
use Inertia\Inertia;

class LearningMaterialController extends Controller
{
    private LearningMaterialRepositoryInterface $learningMaterialRepository;

    private TagRepositoryInterface $tagRepository;

    public function __construct(LearningMaterialRepositoryInterface $learningMaterialRepository, TagRepositoryInterface $tagRepository)
    {
        $this->learningMaterialRepository = $learningMaterialRepository;
        $this->tagRepository = $tagRepository;
        $this->authorizeResource(LearningMaterial::class, 'learning_material');
    }

    public function index(IndexLearningMaterialRequest $request)
    {
        if (! $request->inertia() && $request->expectsJson()) {
            $filters = $request->only(['search', 'filters', 'ai']);
            $learningMaterials = $this->learningMaterialRepository->getForIndex($filters);

            return response()->json(['learning_materials' => $learningMaterials]);
        }

        return to_route('home');
    }

    public function create()
    {
        return Inertia::render('LearningMaterial/CreateShow', [
            'tags' => $this->tagRepository->getTagsForModel(LearningMaterial::class),
        ]);
    }

    public function store(StoreLearningMaterialRequest $request)
    {
        $this->learningMaterialRepository->create($request->validated());
        session()->flash('flash.banner', __('messages.model_created', ['model' => __('models.learningMaterial')]));
        session()->flash('flash.bannerStyle', 'success');

        return to_route('home');
    }

    public function show(LearningMaterial $learningMaterial)
    {
        $learningMaterialWithLoaded = $this->learningMaterialRepository->getById($learningMaterial->id);

        return Inertia::render('LearningMaterial/CreateShow', [
            'learning_material' => $learningMaterialWithLoaded,
            'tags' => $this->tagRepository->getTagsForModel(LearningMaterial::class),
        ]);
    }

    public function update(UpdateLearningMaterialRequest $request, LearningMaterial $learningMaterial)
    {
        $this->learningMaterialRepository->update($learningMaterial, $request->validated());
        session()->flash('flash.banner', __('messages.model_updated', ['model' => __('models.learningMaterial')]));
        session()->flash('flash.bannerStyle', 'success');

        return back();
    }

    public function destroy(LearningMaterial $learningMaterial)
    {
        $this->learningMaterialRepository->delete($learningMaterial);
        session()->flash('flash.banner', __('messages.model_deleted', ['model' => __('models.learningMaterial')]));
        session()->flash('flash.bannerStyle', 'success');

        return to_route('home');
    }
}
