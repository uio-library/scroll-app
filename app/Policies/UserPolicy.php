<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can show the model.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function show(User $user)
    {
        return $user->isSiteAdmin();
    }

    /**
     * Determine whether the user can manage the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function manage(User $user, User $model)
    {
        return $user->isSiteAdmin();
    }
}
