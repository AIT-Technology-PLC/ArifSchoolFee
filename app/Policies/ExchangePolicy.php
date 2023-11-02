<?php

namespace App\Policies;

use App\Models\Exchange;
use App\Models\User;
use App\Traits\VerifyModelIssuer;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExchangePolicy
{
    use HandlesAuthorization, VerifyModelIssuer;

    public function viewAny(User $user)
    {
        return $user->can('Read Exchange');
    }

    public function view(User $user, Exchange $exchange)
    {
        return $user->can('Read Exchange');
    }

    public function create(User $user)
    {
        return $user->can('Create Exchange');
    }

    public function update(User $user, Exchange $exchange)
    {
        return $this->isIssuedByMyBranch($user, $exchange) && $user->can('Update Exchange');
    }

    public function delete(User $user, Exchange $exchange)
    {
        return $this->isIssuedByMyBranch($user, $exchange) && $user->can('Delete Exchange');
    }

    public function approve(User $user, Exchange $exchange)
    {
        return $user->can('Approve Exchange');
    }

    public function execute(User $user, Exchange $exchange)
    {
        return $user->can('Execute Exchange');
    }
}
