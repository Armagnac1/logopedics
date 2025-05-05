<?php

namespace App\Repositories\Contracts;

use App\Models\Lesson;

interface LessonRepositoryInterface
{
    public function getAll();

    public function create(array $data);

    public function find($id);

    public function getSortedLessonsWithIndex(Lesson $lesson): array;

    public function update(Lesson $lesson, array $data);

    public function delete($lesson);

    public function attachLearningMaterials(Lesson $lesson, $materialIds);

    public function findPivotMaterialById($lessonLearningMaterialId);

    public function detachLearningMaterial($lessonLearningMaterialId);

    public function getPupilMaterialHistory(Lesson $lesson);
    public function getGroupedMaterialHistory(Lesson $lesson);
}
