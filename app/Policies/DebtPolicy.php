<?php

namespace App\Policies;

use App\Models\Debt;
use App\Models\User;
use App\Traits\VerifyModelIssuer;
use Illuminate\Auth\Access\HandlesAuthorization;

class DebtPolicy
{
    use HandlesAuthorization, VerifyModelIssuer;

    public function viewAny(User $user)
    {
        return $user->can('Read Debt');
    }

    public function view(User $user, Debt $debt)
    {
        return $user->can('Read Debt');
    }

    public function create(User $user)
    {
        return $user->can('Create Debt');
    }

    public function update(User $user, Debt $debt)
    {
        return $this->isIssuedByMyBranch($user, $debt) && $user->can('Update Debt');
    }

    public function delete(User $user, Debt $debt)
    {
        return $this->isIssuedByMyBranch($user, $debt) && $user->can('Update Debt');
    }

    public function settle(User $user, Debt $debt)
    {
        return $user->can('Settle Debt');
    }

    public function import(User $user)
    {
        return $user->can('Import Debt');
    }
}
