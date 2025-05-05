<?php

namespace App\Repositories\Eloquent;

use App\Models\Lesson;
use App\Repositories\Contracts\LessonRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LessonRepository implements LessonRepositoryInterface
{
    public function getAll()
    {
        return Lesson::all();
    }

    public function create(array $data)
    {
        $dates = $data['start_dates'];
        foreach ($dates as $date) {
            $lesson = new Lesson();
            $lesson = $lesson->fill($data);
            $lesson->start_at = Carbon::parse($date)->setTimeFromTimeString($data['start_time']);
            $lesson->save();
        }

        return $lesson;
    }

    public function getSortedLessonsWithIndex(Lesson $lesson): array
    {
        $sortedLessons = $lesson->pupil->lessons
            ->sortBy([
                fn (Lesson $a, Lesson $b) => strtotime($a['start_at']) <=> strtotime($b['start_at']),
                fn (Lesson $a, Lesson $b) => $b['id'] <=> $a['id'],
            ])->values();

        $currentLessonIndex = $sortedLessons->where('id', $lesson->id)->keys()->first();

        return [
            'sortedLessons' => $sortedLessons,
            'currentLessonIndex' => $currentLessonIndex,
        ];
    }

    public function find($id)
    {
        return Lesson::findOrFail($id);
    }

    public function update($lesson, array $data)
    {
        $lesson->update($data);

        return $lesson;
    }

    public function delete($lesson)
    {
        $lesson->delete();
    }

    public function attachLearningMaterials(Lesson $lesson, $materialIds)
    {
        $lesson->learningMaterials()->attach($materialIds);
    }

    public function findPivotMaterialById($lessonLearningMaterialId)
    {
        return DB::table('lesson_learning_materials')->where('id', $lessonLearningMaterialId)->first();
    }

    public function detachLearningMaterial($lessonLearningMaterialId)
    {
        return DB::table('lesson_learning_materials')->where('id', $lessonLearningMaterialId)->delete();
    }

    public function getPupilMaterialHistory(Lesson $lesson)
    {
        $pupilId = $lesson->pupil_id;

        $lessons = Lesson::where('pupil_id', $pupilId)
            ->with('learningMaterials')
            ->get()
            ->map(function (Lesson $l) use ($lesson) {
                $ids = $l->learningMaterials->pluck('id')->toArray();
                if ($l->id === $lesson->id) {
                    $ids[] = '*';
                }

                return $ids;
            });

        return $lessons;
    }

    public function getGroupedMaterialHistory(Lesson $lesson)
    {
        $currentPupilId = $lesson->pupil_id;

        $grouped = Lesson::with(['pupil', 'learningMaterials'])
            ->get()
            ->filter(fn ($l) => $l->pupil && $l->learningMaterials->isNotEmpty() && $l->pupil->id !== $currentPupilId
            )
            ->groupBy(fn ($l) => $l->pupil->id)
            ->map(fn ($lessons) => $lessons->map(fn ($l) => $l->learningMaterials->pluck('id')->toArray()
            )
            );

        return $grouped;
    }
}
