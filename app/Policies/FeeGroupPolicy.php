<?php

namespace App\Policies;

use App\Models\FeeGroup;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FeeGroupPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Fee Group');
    }

    public function view(User $user, FeeGroup $feeGroup)
    {
        return $user->can('Read Fee Group');
    }

    public function create(User $user)
    {
        return $user->can('Create Fee Group');
    }

    public function update(User $user, FeeGroup $feeGroup)
    {
        return $user->can('Update Fee Group');
    }

    public function delete(User $user, FeeGroup $feeGroup)
    {
        return $user->can('Delete Fee Group');
    }

    public function import(User $user)
    {
        return $user->can('Import Fee Group');
    }
}
