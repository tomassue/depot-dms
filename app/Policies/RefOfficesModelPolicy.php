<?php

namespace App\Policies;

use App\Models\RefOfficesModel;
use App\Models\User;

class RefOfficesModelPolicy
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
        return $user->can('create offices');
    }

    /**
     * Determine if the user can read offices.
     */
    public function read(User $user)
    {
        return $user->can('read offices');
    }

    /**
     * Determine if the user can update a specific office.
     */
    public function update(User $user, RefOfficesModel $office)
    {
        return $user->can('update offices');
    }

    /**
     * Determine if the user can delete a specific office.
     */
    public function delete(User $user, RefOfficesModel $office)
    {
        return $user->can('delete offices');
    }

    /**
     * Determine if the user can restore a soft-deleted office.
     */
    public function restore(User $user, RefOfficesModel $office)
    {
        return $user->can('restore offices') && $office->trashed();
    }
}
