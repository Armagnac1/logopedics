<?php

namespace App\Policies;

use App\Models\Lesson;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LessonPolicy
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
    public function view(User $user, Lesson $lesson): Response
    {
        return $lesson->pupil->tutor->id === $user->tutor->id
            ? Response::allow()
            : Response::deny('You are not allowed to view this lesson.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create lesson');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Lesson $lesson): Response
    {
        return $lesson->pupil->tutor->id === $user->tutor->id
            ? Response::allow()
            : Response::deny('You are not allowed to delete this lesson.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Lesson $lesson): Response
    {
        return $user->can('update lesson') && $lesson->pupil->tutor->id === $user->tutor->id
            ? Response::allow()
            : Response::deny('You are not allowed to update this lesson.');
    }
}
