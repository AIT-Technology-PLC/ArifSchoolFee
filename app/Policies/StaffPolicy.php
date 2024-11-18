<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Staff;
use Illuminate\Auth\Access\HandlesAuthorization;

class StaffPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Staff');
    }

    public function view(User $user, Staff $staff)
    {
        return $user->can('Read Staff');
    }

    public function create(User $user)
    {
        return $user->can('Create Staff');
    }

    public function update(User $user, Staff $staff)
    {
        return $user->can('Update Staff');
    }

    public function delete(User $user, Staff $staff)
    {
        return $user->can('Delete Staff');
    }

    public function import(User $user)
    {
        return $user->can('Import Staff');
    }
}
