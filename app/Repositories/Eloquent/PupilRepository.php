<?php

namespace App\Repositories\Eloquent;

use App\Enums\LessonStatus;
use App\Models\Lesson;
use App\Models\Pupil;
use App\Repositories\Contracts\PupilRepositoryInterface;

class PupilRepository implements PupilRepositoryInterface
{
    public function getForIndex($search)
    {
        $tutorId = auth()->user()->tutor->id;
        $upcomingLessons = \DB::table('lessons')
            ->selectRaw('pupil_id, MIN(start_at) as start_at')
            ->where('status', LessonStatus::SCHEDULED)
            ->where('lessons.start_at', '>', now())
            ->groupByRaw(1);

        return Pupil::when($search, function ($query) use ($search) {
            $pupilsIds = Pupil::search($search)->keys();
            $query->whereIn('pupils.id', $pupilsIds);
        })
            ->leftJoinSub($upcomingLessons, 'lessons', function ($join) {
                $join->on('pupils.id', '=', 'lessons.pupil_id');
            })
            ->where('tutor_id', $tutorId)
            ->with(['lessons', 'user'])
            ->orderBy('lessons.start_at', 'ASC')
            ->paginate(30)->withQueryString();
    }

    public function getPupilWithRelations(Pupil $pupil)
    {
        $pupil->load(['tags', 'lessons.learningMaterials.tags', 'city']);
        $pupil->setRelation('lessons', $pupil->lessons->sortBy([
            fn (Lesson $a, Lesson $b) => strtotime($a['start_at']) <=> strtotime($b['start_at']),
            fn (Lesson $a, Lesson $b) => $b['id'] <=> $a['id'],
        ])->values());

        return $pupil;
    }

    public function getAllIdsWithFullNames()
    {
        return Pupil::select(['id', 'full_name'])->get()->toArray();
    }

    public function create(array $data)
    {
        $pupil = new Pupil($data);
        $pupil->tutor_id = auth()->user()->tutor->id;
        $pupil->save();

        return $pupil;
    }

    public function update(Pupil $pupil, array $data)
    {
        $pupil->update($data);

        return $pupil;
    }

    public function delete(Pupil $pupil)
    {
        $pupil->delete();
    }
}
