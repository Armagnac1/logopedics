<?php

namespace App\Repositories\Abstracts;

use App\Models\Pupil;

interface PupilRepositoryInterface
{
    public function getForIndex($search);

    public function getPupilWithRelations(Pupil $pupil);

    public function create(array $data);

    public function update(Pupil $pupil, array $data);

    public function delete(Pupil $pupil);
}
