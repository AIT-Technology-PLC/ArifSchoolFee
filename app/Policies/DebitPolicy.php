<?php

namespace App\Policies;

use App\Models\Debit;
use App\Models\User;
use App\Traits\VerifyModelIssuer;
use Illuminate\Auth\Access\HandlesAuthorization;

class DebitPolicy
{
    use HandlesAuthorization, VerifyModelIssuer;

    public function viewAny(User $user)
    {
        return $user->can('Read Debit');
    }

    public function view(User $user, Debit $debit)
    {
        return $user->can('Read Debit');
    }

    public function create(User $user)
    {
        return $user->can('Create Debit');
    }

    public function update(User $user, Debit $debit)
    {
        return $this->isIssuedByMyBranch($user, $debit) && $user->can('Update Debit');
    }

    public function delete(User $user, Debit $debit)
    {
        return $this->isIssuedByMyBranch($user, $debit) && $user->can('Update Debit');
    }

    public function settle(User $user, Debit $debit)
    {
        return $user->can('Settle Debit');
    }
}
