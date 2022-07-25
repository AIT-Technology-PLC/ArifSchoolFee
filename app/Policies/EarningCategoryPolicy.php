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
        return $user->can('Read Earning');
    }

    public function view(User $user, EarningCategory $earningCategory)
    {
        return $user->can('Read Earning');
    }

    public function create(User $user)
    {
        return $user->can('Create Earning');
    }

    public function update(User $user, EarningCategory $earningCategory)
    {
        return $user->can('Update Earning');
    }

    public function delete(User $user, EarningCategory $earningCategory)
    {
        return $user->can('Delete Earning');
    }
}
