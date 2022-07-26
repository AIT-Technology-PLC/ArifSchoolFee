<?php

namespace App\Policies;

use App\Models\Earning;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EarningPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Earning');
    }

    public function view(User $user, Earning $earning)
    {
        return $user->can('Read Earning');
    }

    public function create(User $user)
    {
        return $user->can('Create Earning');
    }

    public function update(User $user)
    {
        return $user->can('Update Earning');
    }

    public function delete(User $user, Earning $earning)
    {
        return $user->can('Delete Earning');
    }

    public function approve(User $user, Earning $earning)
    {
        return $user->can('Approve Earning');
    }
}
