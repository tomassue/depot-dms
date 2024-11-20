<?php

namespace App\Policies;

use App\Models\TblIncomingRequestModel;
use App\Models\User;

class TblIncomingRequestModelPolicy
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
        return $user->can('create incoming');
    }

    public function read(User $user)
    {
        return $user->can('read incoming');
    }

    public function update(User $user, TblIncomingRequestModel $incoming_request)
    {
        return $user->can('update incoming');
    }
}
