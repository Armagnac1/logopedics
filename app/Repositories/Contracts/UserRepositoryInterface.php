<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
    public function getForIndex($perPage = 20);
    public function getTutorId($userId): ?int;
}
