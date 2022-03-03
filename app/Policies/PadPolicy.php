<?php

namespace App\Policies;

use App\Models\Pad;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PadPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole('System Manager') && $user->can('Read Pad');
    }

    public function view(User $user, Pad $pad)
    {
        return $user->hasRole('System Manager') && $user->can('Read Pad');
    }

    public function create(User $user)
    {
        return $user->hasRole('System Manager') && $user->can('Create Pad');
    }

    public function update(User $user, Pad $pad)
    {
        return $user->hasRole('System Manager') && $user->can('Update Pad');
    }

    public function delete(User $user, Pad $pad)
    {
        return $user->hasRole('System Manager') && $user->can('Delete Pad');
    }
}
