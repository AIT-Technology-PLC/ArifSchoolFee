<?php

namespace App\Policies;

use App\Models\Advancement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdvancementPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Advancement');
    }

    public function view(User $user, Advancement $advancement)
    {
        return $user->can('Read Advancement');
    }

    public function create(User $user)
    {
        return $user->can('Create Advancement');
    }

    public function update(User $user)
    {
        return $user->can('Update Advancement');
    }

    public function delete(User $user, Advancement $advancement)
    {
        return $user->can('Delete Advancement');
    }

    public function approve(User $user, Advancement $advancement)
    {
        return $user->can('Approve Advancement');
    }

    public function cancel(User $user, Advancement $advancement)
    {
        return $user->can('Cancel Advancement');
    }
}
