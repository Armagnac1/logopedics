<?php

namespace App\Repositories\Contracts;

use App\Models\Pupil;

interface PupilRepositoryInterface
{
    public function getForIndex($search);

    public function getPupilWithRelations(Pupil $pupil);

    public function create(array $data);

    public function getAllIdsWithFullNames();

    public function update(Pupil $pupil, array $data);

    public function delete(Pupil $pupil);
}
