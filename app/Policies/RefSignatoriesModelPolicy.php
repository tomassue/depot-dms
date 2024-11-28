<?php

namespace App\Policies;

use App\Models\RefSignatoriesModel;
use App\Models\User;

class RefSignatoriesModelPolicy
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
        return $user->can('create signatory');
    }

    public function read(User $user)
    {
        return $user->can('read signatory');
    }

    public function update(User $user, RefSignatoriesModel $signatory)
    {
        return $user->can('update signatory');
    }

    public function delete(User $user, RefSignatoriesModel $signatory)
    {
        return $user->can('delete signatory');
    }

    public function restore(User $user, RefSignatoriesModel $signatory)
    {
        return $user->can('restore signatory') && $signatory->trashed();
    }
}
