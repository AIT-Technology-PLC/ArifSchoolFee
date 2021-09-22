<?php

namespace App\Policies;

use App\Models\Grn;
use App\Traits\ModelToCompanyBelongingnessChecker;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GrnPolicy
{
    use HandlesAuthorization, ModelToCompanyBelongingnessChecker;

    public function viewAny(User $user)
    {
        return $user->can('Read GRN');
    }

    public function view(User $user, Grn $grn)
    {
        return $this->doesModelBelongToMyCompany($user, $grn) && $user->can('Read GRN');
    }

    public function create(User $user)
    {
        return $user->can('Create GRN');
    }

    public function update(User $user, Grn $grn)
    {
        return $this->doesModelBelongToMyCompany($user, $grn) && $user->can('Update GRN');
    }

    public function delete(User $user, Grn $grn)
    {
        return $this->doesModelBelongToMyCompany($user, $grn) && $user->can('Delete GRN');
    }

    public function approve(User $user, Grn $grn)
    {
        return $this->doesModelBelongToMyCompany($user, $grn) && $user->can('Approve GRN');
    }

    public function add(User $user, Grn $grn)
    {
        return $this->doesModelBelongToMyCompany($user, $grn) && $user->can('Add GRN');
    }
}
