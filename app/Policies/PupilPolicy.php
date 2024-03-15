<?php

namespace App\Policies;

use App\Models\Pupil;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PupilPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Pupil $pupil): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create/change pupil');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Pupil $pupil): Response
    {
        return $user->can('create/change pupil') ? Response::allow()
            : Response::deny('Нет доступа к редактированию ученика');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Pupil $pupil): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Pupil $pupil): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Pupil $pupil): bool
    {
        //
    }
}
