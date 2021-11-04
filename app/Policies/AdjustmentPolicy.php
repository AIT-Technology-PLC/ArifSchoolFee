<?php

namespace App\Policies;

use App\Models\Adjustment;
use App\Models\User;
use App\Traits\VerifyModelIssuer;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdjustmentPolicy
{
    use HandlesAuthorization, VerifyModelIssuer;

    public function viewAny(User $user)
    {
        return $user->can('Read Adjustment');
    }

    public function view(User $user, Adjustment $adjustment)
    {
        return $this->isIssuedByMyCompany($user, $adjustment) && $user->can('Read Adjustment');
    }

    public function create(User $user)
    {
        return $user->can('Create Adjustment');
    }

    public function update(User $user, Adjustment $adjustment)
    {
        return $this->isIssuedByMyCompany($user, $adjustment) && $user->can('Update Adjustment');
    }

    public function delete(User $user, Adjustment $adjustment)
    {
        return $this->isIssuedByMyCompany($user, $adjustment, true) && $user->can('Delete Adjustment');
    }

    public function approve(User $user, Adjustment $adjustment)
    {
        return $this->isIssuedByMyCompany($user, $adjustment) && $user->can('Approve Adjustment');
    }

    public function adjust(User $user, Adjustment $adjustment)
    {
        return $this->isIssuedByMyCompany($user, $adjustment) && $user->can('Make Adjustment');
    }
}
