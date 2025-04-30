<?php

namespace App\Repositories\Abstracts;

use App\Models\Pupil;

interface PupilRepositoryInterface
{
    public function getForIndex($search);
    public function getPupilWithRelations(Pupil $pupil);

    public function createPupil(array $data);

    public function updatePupil(Pupil $pupil, array $data);

    public function deletePupil(Pupil $pupil);
}
