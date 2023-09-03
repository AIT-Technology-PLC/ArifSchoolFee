<?php

namespace App\Policies;

use App\Models\Credit;
use App\Models\User;
use App\Traits\VerifyModelIssuer;
use Illuminate\Auth\Access\HandlesAuthorization;

class CreditPolicy
{
    use HandlesAuthorization, VerifyModelIssuer;

    public function viewAny(User $user)
    {
        return $user->can('Read Credit');
    }

    public function view(User $user, Credit $credit)
    {
        return $user->can('Read Credit');
    }

    public function create(User $user)
    {
        return $user->can('Create Credit');
    }

    public function update(User $user, Credit $credit)
    {
        return $this->isIssuedByMyBranch($user, $credit) && $user->can('Update Credit');
    }

    public function delete(User $user, Credit $credit)
    {
        return $this->isIssuedByMyBranch($user, $credit) && $user->can('Update Credit');
    }

    public function settle(User $user, Credit $credit)
    {
        return $user->can('Settle Credit');
    }

    public function import(User $user)
    {
        return $user->can('Import Credit');
    }
}
