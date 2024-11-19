<?php

namespace App\Policies;

use App\Models\RefModelModel;
use App\Models\User;

class RefModelModelPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function create(User $user)
    {
        return $user->can('create models');
    }

    public function read(User $user)
    {
        return $user->can('read models');
    }

    public function update(User $user, RefModelModel $model)
    {
        return $user->can('update models');
    }

    public function delete(User $user, RefModelModel $model)
    {
        return $user->can('delete models');
    }

    public function restore(User $user, RefModelModel $model)
    {
        return $user->can('restore models') && $model->trashed();
    }
}
