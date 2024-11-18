<?php

namespace App\Policies;

use App\Models\RefSubCategoryModel;
use App\Models\User;

class RefSubCategoryModelPolicy
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
        return $user->can('create sub-category');
    }

    public function read(User $user)
    {
        return $user->can('read sub-category');
    }

    public function update(User $user, RefSubCategoryModel $subCategory)
    {
        return $user->can('update sub-category');
    }

    public function delete(User $user, RefSubCategoryModel $subCategory)
    {
        return $user->can('delete sub-category');
    }

    public function restore(User $user, RefSubCategoryModel $subCategory)
    {
        return $user->can('restore sub-category') && $subCategory->trashed();
    }
}
