<?php

namespace App\Policies;

use App\Models\RefStatusModel;
use App\Models\User;

class RefStatusModelPolicy
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
        return $user->can('create status');
    }

    public function read(User $user)
    {
        return $user->can('read status');
    }

    public function update(User $user, RefStatusModel $status)
    {
        return $user->can('update status');
    }

    public function delete(User $user, RefStatusModel $status)
    {
        return $user->can('delete status');
    }

    public function restore(User $user, RefStatusModel $status)
    {
        return $user->can('restore status') && $status->trashed();
    }
}
