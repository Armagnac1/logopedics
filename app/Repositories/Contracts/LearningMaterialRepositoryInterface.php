<?php

namespace App\Repositories\Contracts;

use App\Models\LearningMaterial;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;

interface LearningMaterialRepositoryInterface
{
    public function getForIndex(array $filters): Paginator;

    public function getById(int $id): ?LearningMaterial;

    public function create(array $data): LearningMaterial;

    public function update(LearningMaterial $learningMaterial, array $data): bool;

    public function delete(LearningMaterial $learningMaterial): bool;

    public function getSuggestionsForLesson(int $lessonId): Collection;
}
