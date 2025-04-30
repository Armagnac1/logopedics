<?php

namespace App\Policies;

use App\Models\LearningMaterial;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LearningMaterialPolicy
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
    public function view(User $user, LearningMaterial $learningMaterial): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LearningMaterial $learningMaterial): Response
    {
        return $learningMaterial->creator_user_id === $user->id
            ? Response::allow()
            : Response::deny('You are not allowed to update this material');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LearningMaterial $learningMaterial): Response
    {
        return $learningMaterial->creator_user_id === $user->id
            ? Response::allow()
            : Response::deny('You are not allowed to update this material');
    }
}
