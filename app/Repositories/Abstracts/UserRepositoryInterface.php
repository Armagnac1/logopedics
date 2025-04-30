<?php

namespace App\Repositories\Abstracts;

interface UserRepositoryInterface
{
    public function getForIndex($perPage = 20);
}
