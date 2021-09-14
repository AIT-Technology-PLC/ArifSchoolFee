<?php

namespace App\Policies;

use App\Models\Adjustment;
use App\Traits\ModelToCompanyBelongingnessChecker;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdjustmentPolicy
{
    use HandlesAuthorization, ModelToCompanyBelongingnessChecker;

    public function viewAny(User $user)
    {
        return $user->can('Read Adjustment');
    }

    public function view(User $user, Adjustment $adjustment)
    {
        return $this->doesModelBelongToMyCompany($user, $adjustment) && $user->can('Read Adjustment');
    }

    public function create(User $user)
    {
        return $user->can('Create Adjustment');
    }

    public function update(User $user, Adjustment $adjustment)
    {
        return $this->doesModelBelongToMyCompany($user, $adjustment) && $user->can('Update Adjustment');
    }

    public function delete(User $user, Adjustment $adjustment)
    {
        return $this->doesModelBelongToMyCompany($user, $adjustment) && $user->can('Delete Adjustment');
    }

    public function approve(User $user, Adjustment $adjustment)
    {
        return $this->doesModelBelongToMyCompany($user, $adjustment) && $user->can('Approve Adjustment');
    }

    public function adjust(User $user, Adjustment $adjustment)
    {
        return $this->doesModelBelongToMyCompany($user, $adjustment) && $user->can('Make Adjustment');
    }
}
