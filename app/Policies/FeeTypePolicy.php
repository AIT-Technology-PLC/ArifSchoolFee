<?php

namespace App\Policies;

use App\Models\FeeType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FeeTypePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Fee Type');
    }

    public function view(User $user, FeeType $feeType)
    {
        return $user->can('Read Fee Type');
    }

    public function create(User $user)
    {
        return $user->can('Create Fee Type');
    }

    public function update(User $user, FeeType $feeType)
    {
        return $user->can('Update Fee Type');
    }

    public function delete(User $user, FeeType $feeType)
    {
        return $user->can('Delete Fee Type');
    }

    public function import(User $user)
    {
        return $user->can('Import Fee Type');
    }
}
