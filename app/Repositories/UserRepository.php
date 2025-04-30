<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Abstracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function getForIndex($perPage = 20)
    {
        return User::orderBy('id', 'asc')
            ->with('roles')
            ->paginate($perPage)
            ->withQueryString();
    }
}
