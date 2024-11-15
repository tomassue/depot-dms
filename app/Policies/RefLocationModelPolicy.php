<?php

namespace App\Policies;

use App\Models\RefLocationModel;
use App\Models\User;

class RefLocationModelPolicy
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
        return $user->can('can create location');
    }

    public function update(User $user, RefLocationModel $location)
    {
        return $user->can('can update location');
    }

    public function delete(User $user, RefLocationModel $location)
    {
        return $user->can('delete location');
    }

    public function restore(User $user, RefLocationModel $location)
    {
        return $user->can('restore location');
    }
}
