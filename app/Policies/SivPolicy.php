<?php

namespace App\Policies;

use App\Models\Siv;
use App\Models\User;
use App\Traits\VerifyModelIssuer;
use Illuminate\Auth\Access\HandlesAuthorization;

class SivPolicy
{
    use HandlesAuthorization, VerifyModelIssuer;

    public function viewAny(User $user)
    {
        return $user->can('Read SIV');
    }

    public function view(User $user, Siv $siv)
    {
        return $user->can('Read SIV');
    }

    public function create(User $user)
    {
        return $user->can('Create SIV');
    }

    public function update(User $user, Siv $siv)
    {
        $permission = 'Update SIV';

        if ($siv->isApproved() && $siv->company->canSivSubtract() && !$siv->isSubtracted()) {
            $permission = 'Update Approved SIV';
        }

        return $this->isIssuedByMyBranch($user, $siv) && $user->can($permission);
    }

    public function delete(User $user, Siv $siv)
    {
        return $this->isIssuedByMyBranch($user, $siv) && $user->can('Delete SIV');
    }

    public function approve(User $user, Siv $siv)
    {
        return $user->can('Approve SIV');
    }

    public function subtract(User $user, Siv $siv)
    {
        return $user->can('Subtract SIV');
    }
}
