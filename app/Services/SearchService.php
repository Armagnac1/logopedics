<?php

namespace App\Services;

use App\Models\LearningMaterial;
use App\Models\Lesson;
use App\Models\Pupil;
use App\Services\Abstracts\SearchServiceInterface;

class SearchService implements SearchServiceInterface
{
    public function getForIndex(?string $searchInput = null): array
    {
        if (! $searchInput) {
            return [
                'pupils' => Pupil::limit(5)->with(['user'])->get(),
                'lessons' => Lesson::limit(5)->get(),
                'learningMaterials' => LearningMaterial::limit(5)->with(['tags'])->get(),
            ];
        }

        $pupils = Pupil::search($searchInput)->query(function ($builder) {
            $builder->with(['user']);
        })->take(5)->get();

        $lessons = Lesson::search($searchInput)->take(5)->get();

        $learningMaterials = LearningMaterial::search($searchInput)->query(function ($builder) {
            $builder->with(['tags']);
        })->take(5)->get();

        return [
            'pupils' => $pupils,
            'lessons' => $lessons,
            'learningMaterials' => $learningMaterials,
        ];
    }
}
