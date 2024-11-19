<?php

namespace App\Policies;

use App\Models\RefTypeModel;
use App\Models\User;

class RefTypeModelPolicy
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
        return $user->can('create types');
    }

    public function read(User $user)
    {
        return $user->can('read types');
    }

    public function update(User $user, RefTypeModel $type)
    {
        return $user->can('update types');
    }

    public function delete(User $user, RefTypeModel $type)
    {
        return $user->can('delete types');
    }

    public function restore(User $user, RefTypeModel $type)
    {
        return $user->can('restore types') && $type->trashed();
    }
}
