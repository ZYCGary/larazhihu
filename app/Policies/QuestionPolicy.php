<?php

namespace App\Policies;

use App\Models\Question;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Question $question
     */
    public function view(User $user, Question $question)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Question $question
     * @return bool
     */
    public function update(User $user, Question $question): bool
    {
        return (int)$user->id === (int)$question->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Question $question
     */
    public function delete(User $user, Question $question)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Question $question
     */
    protected function restore(User $user, Question $question)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Question $question
     */
    public function forceDelete(User $user, Question $question)
    {
        //
    }
}
