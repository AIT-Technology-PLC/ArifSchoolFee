<?php

namespace App\Policies;

use App\Models\Damage;
use App\Models\User;
use App\Traits\VerifyModelIssuer;
use Illuminate\Auth\Access\HandlesAuthorization;

class DamagePolicy
{
    use HandlesAuthorization, VerifyModelIssuer;

    public function viewAny(User $user)
    {
        return $user->can('Read Damage');
    }

    public function view(User $user, Damage $damage)
    {
        return $user->can('Read Damage');
    }

    public function create(User $user)
    {
        return $user->can('Create Damage');
    }

    public function update(User $user, Damage $damage)
    {
        $permission = 'Update Damage';

        if ($damage->isApproved() && !$damage->isSubtracted()) {
            $permission = 'Update Approved Damage';
        }

        return $this->isIssuedByMyBranch($user, $damage) && $user->can($permission);
    }

    public function delete(User $user, Damage $damage)
    {
        return $this->isIssuedByMyBranch($user, $damage) && $user->can('Delete Damage');
    }

    public function approve(User $user, Damage $damage)
    {
        return $user->can('Approve Damage');
    }

    public function subtract(User $user, Damage $damage)
    {
        return $user->can('Subtract Damage');
    }
}
