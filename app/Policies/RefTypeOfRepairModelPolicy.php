<?php

namespace App\Policies;

use App\Models\RefTypeOfRepairModel;
use App\Models\User;

class RefTypeOfRepairModelPolicy
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
        return $user->can('create type of repair');
    }

    public function read(User $user)
    {
        return $user->can('read type of repair');
    }

    /**
     * Determine if the user can update a specific office.
     */
    public function update(User $user, RefTypeOfRepairModel $type_of_repair)
    {
        return $user->can('update type of repair');
    }

    /**
     * Determine if the user can delete a specific office.
     */
    public function delete(User $user, RefTypeOfRepairModel $type_of_repair)
    {
        return $user->can('delete type of repair');
    }

    /**
     * Determine if the user can restore a soft-deleted office.
     */
    public function restore(User $user, RefTypeOfRepairModel $type_of_repair)
    {
        return $user->can('restore type of repair') && $type_of_repair->trashed();
    }
}
