<?php

namespace App\Policies;

use App\Models\Compensation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompensationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Compensation');
    }

    public function view(User $user, Compensation $compensation)
    {
        return $user->can('Read Compensation');
    }

    public function create(User $user)
    {
        return $user->can('Create Compensation');
    }

    public function update(User $user)
    {
        return $user->can('Update Compensation');
    }

    public function delete(User $user, Compensation $compensation)
    {
        return $user->can('Delete Compensation');
    }
}