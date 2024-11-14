<?php

namespace App\Policies;

use App\Models\RefCategoryModel;
use App\Models\User;

class RefCategoryModelPolicy
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
        return $user->can('can create category');
    }

    public function update(User $user, RefCategoryModel $category)
    {
        return $user->can('can update category');
    }

    public function delete(User $user, RefCategoryModel $category)
    {
        return $user->can('delete category');
    }

    public function restore(User $user, RefCategoryModel $category)
    {
        return $user->can('restore category');
    }
}
