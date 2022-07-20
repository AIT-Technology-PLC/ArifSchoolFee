<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Warning;
use Illuminate\Auth\Access\HandlesAuthorization;

class WarningPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Warning');
    }

    public function view(User $user, Warning $warning)
    {
        return $user->can('Read Warning');
    }

    public function create(User $user)
    {
        return $user->can('Create Warning');
    }

    public function update(User $user)
    {
        return $user->can('Update Warning');
    }

    public function delete(User $user, Warning $warning)
    {
        return $user->can('Delete Warning');
    }

    public function approve(User $user, Warning $warning)
    {
        return $user->can('Approve Warning');
    }
}
