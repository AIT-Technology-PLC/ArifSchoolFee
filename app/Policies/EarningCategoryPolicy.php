<?php

namespace App\Policies;

use App\Models\EarningCategory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EarningCategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Earning Category');
    }

    public function view(User $user, EarningCategory $category)
    {
        return $user->can('Read Earning Category');
    }

    public function create(User $user)
    {
        return $user->can('Create Earning Category');
    }

    public function update(User $user, EarningCategory $category)
    {
        return $user->can('Update Earning Category');
    }

    public function delete(User $user, EarningCategory $category)
    {
        return $user->can('Delete Earning Category');
    }
}
