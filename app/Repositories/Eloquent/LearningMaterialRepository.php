<?php

namespace App\Repositories\Eloquent;

use App\Models\LearningMaterial;
use App\Models\Lesson;
use App\Repositories\Contracts\LearningMaterialRepositoryInterface;
use App\Services\CrossDomain\Suggestions\SuggestionsServiceInterface;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class LearningMaterialRepository implements LearningMaterialRepositoryInterface
{

    public function __construct(private SuggestionsServiceInterface $aiSuggestionsService)
    {
    }

    public function getForIndex(array $filters): \Illuminate\Contracts\Pagination\Paginator
    {
        $searchInput = $filters['search'] ?? null;
        $useAI = $filters['ai'] ?? false;

        if ($useAI) {
            $lesson = Lesson::find($filters['filters']['lessonId']);
            $resultIds = $this->aiSuggestionsService->getLearningMaterialSuggestions($lesson);

            return LearningMaterial::whereIn('id', $resultIds)->with(['tags', 'media'])->simplePaginate(10);
        }

        return LearningMaterial::search($searchInput)->query(function ($builder) use ($filters) {
            $builder->with(['tags', 'media'])
                ->when($filters['filters']['onlyNotUsed'] === '1', function ($query) use ($filters) {
                    $lesson = Lesson::find($filters['filters']['lessonId']);
                    $usedLearningMaterialsIds = $lesson->pupil->lessons->pluck('learningMaterials')->flatten()->pluck('id')->toArray();
                    $query->whereNotIn('id', $usedLearningMaterialsIds);
                })->when(isset($filters['filters']['tags']) && count($filters['filters']['tags']) > 0, function ($query) use ($filters) {
                    $ids = collect($filters['filters']['tags'])->pluck('id');
                    $query->whereHas('tags', function ($query) use ($ids) {
                        $query->whereIn('id', $ids);
                    }, '=', count($ids));
                });
        })->simplePaginate(10);
    }

    public function getById(int $id): ?LearningMaterial
    {
        return LearningMaterial::with(['tags', 'media', 'lessons.pupil'])->find($id);
    }

    public function create(array $data): LearningMaterial
    {
        $learningMaterial = new LearningMaterial($data);
        $learningMaterial->creator_user_id = auth()->id();
        $learningMaterial->save();

        $tags = collect($data['tags'])->pluck('id')->toArray();
        $learningMaterial->tags()->sync($tags);

        Media::whereIn('id', $data['mediaIds'])
            ->get()
            ->each(function ($media) use ($learningMaterial) {
                $media->model_id = $learningMaterial->id;
                $media->model_type = LearningMaterial::class;
                $media->save();
            });

        return $learningMaterial;
    }

    public function update(LearningMaterial $learningMaterial, array $data): bool
    {
        $learningMaterial->media
            ->reject(fn(Media $media) => in_array($media->id, $data['mediaIds']))
            ->each(function ($media) {
                $media->delete();
            });

        Media::whereIn('id', $data['mediaIds'])
            ->get()
            ->each(function ($media) use ($learningMaterial) {
                $media->model_id = $learningMaterial->id;
                $media->model_type = LearningMaterial::class;
                $media->save();
            });

        $tags = collect($data['tags'])->pluck('id')->toArray();
        $learningMaterial->tags()->sync($tags);

        return $learningMaterial->update($data);
    }

    public function delete(LearningMaterial $learningMaterial): bool
    {
        return $learningMaterial->delete();
    }

    public function getSuggestionsForLesson(int $lessonId): Collection
    {
        $lesson = Lesson::find($lessonId);
        $resultIds = $this->aiSuggestionsService->getLearningMaterialSuggestions($lesson);

        return LearningMaterial::whereIn('id', $resultIds)->get();
    }
}
