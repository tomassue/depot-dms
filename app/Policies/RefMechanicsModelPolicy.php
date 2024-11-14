<?php

namespace App\Policies;

use App\Models\RefMechanicsModel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RefMechanicsModelPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function create(User $user)
    {
        return $user->can('can create mechanics');
    }

    public function update(User $user, RefMechanicsModel $mechanic)
    {
        return $user->can('can update mechanics');
    }

    public function delete(User $user, RefMechanicsModel $mechanic)
    {
        return $user->can('can delete mechanics');
    }

    public function restore(User $user, RefMechanicsModel $mechanic)
    {
        return $user->can('can restore mechanics');
    }
}
