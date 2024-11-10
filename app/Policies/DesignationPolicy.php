<?php

namespace App\Policies;

use App\Models\Designation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DesignationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Designation');
    }

    public function view(User $user, Designation $designation)
    {
        return $user->can('Read Designation');
    }

    public function create(User $user)
    {
        return $user->can('Create Designation');
    }

    public function update(User $user, Designation $designation)
    {
        return $user->can('Update Designation');
    }

    public function delete(User $user, Designation $designation)
    {
        return $user->can('Delete Designation');
    }
}