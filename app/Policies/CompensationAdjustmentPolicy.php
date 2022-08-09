<?php

namespace App\Policies;

use App\Models\CompensationAdjustment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompensationAdjustmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Compensation Adjustment');
    }

    public function view(User $user, CompensationAdjustment $compensationAdjustment)
    {
        return $user->can('Read Compensation Adjustment');
    }

    public function create(User $user)
    {
        return $user->can('Create Compensation Adjustment');
    }

    public function update(User $user, CompensationAdjustment $compensationAdjustment)
    {
        return $user->can('Update Compensation Adjustment');
    }

    public function delete(User $user, CompensationAdjustment $compensationAdjustment)
    {
        return $user->can('Delete Compensation Adjustment');
    }

    public function approve(User $user, CompensationAdjustment $compensationAdjustment)
    {
        return $user->can('Approve Compensation Adjustment');
    }

    public function cancel(User $user, CompensationAdjustment $compensationAdjustment)
    {
        return $user->can('Cancel Compensation Adjustment');
    }
}