<?php

namespace App\Policies;

use App\Models\Gdn;
use App\Traits\ModelToCompanyBelongingnessChecker;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GdnPolicy
{
    use HandlesAuthorization, ModelToCompanyBelongingnessChecker;

    public function viewAny(User $user)
    {
        return $user->can('Read GDN');
    }

    public function view(User $user, Gdn $gdn)
    {
        return $this->doesModelBelongToMyCompany($user, $gdn) && $user->can('Read GDN');
    }

    public function create(User $user)
    {
        return $user->can('Create GDN');
    }

    public function update(User $user, Gdn $gdn)
    {
        return $this->doesModelBelongToMyCompany($user, $gdn) && $user->can('Update GDN');
    }

    public function delete(User $user, Gdn $gdn)
    {
        return $this->doesModelBelongToMyCompany($user, $gdn) && $user->can('Delete GDN');
    }

    public function approve(User $user, Gdn $gdn)
    {
        return $this->doesModelBelongToMyCompany($user, $gdn) && $user->can('Approve GDN');
    }

    public function subtract(User $user, Gdn $gdn)
    {
        return $this->doesModelBelongToMyCompany($user, $gdn) && $user->can('Subtract GDN');
    }
}
