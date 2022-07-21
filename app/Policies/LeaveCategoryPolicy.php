<?php

namespace App\Policies;

use App\Models\LeaveCategory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeaveCategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Leave');
    }

    public function view(User $user, LeaveCategory $leaveCategory)
    {
        return $user->can('Read Leave');
    }

    public function create(User $user)
    {
        return $user->can('Create Leave');
    }

    public function update(User $user, LeaveCategory $leaveCategory)
    {
        return $user->can('Update Leave');
    }

    public function delete(User $user, LeaveCategory $leaveCategory)
    {
        return $user->can('Delete Leave');
    }
}