<?php

namespace App\Policies;

use App\Models\Grn;
use App\Models\User;
use App\Traits\VerifyModelIssuer;
use Illuminate\Auth\Access\HandlesAuthorization;

class GrnPolicy
{
    use HandlesAuthorization, VerifyModelIssuer;

    public function viewAny(User $user)
    {
        return $user->can('Read GRN');
    }

    public function view(User $user, Grn $grn)
    {
        return $user->can('Read GRN');
    }

    public function create(User $user)
    {
        return $user->can('Create GRN');
    }

    public function update(User $user, Grn $grn)
    {
        $permission = 'Update GRN';

        if ($grn->isApproved() && !$grn->isAdded()) {
            $permission = 'Update Approved GRN';
        }

        return $this->isIssuedByMyBranch($user, $grn) && $user->can($permission);
    }

    public function delete(User $user, Grn $grn)
    {
        return $this->isIssuedByMyBranch($user, $grn) && $user->can('Delete GRN');
    }

    public function approve(User $user, Grn $grn)
    {
        return $user->can('Approve GRN');
    }

    public function add(User $user, Grn $grn)
    {
        return $user->can('Add GRN');
    }

    public function import(User $user)
    {
        return $user->can('Import GRN');
    }
}
