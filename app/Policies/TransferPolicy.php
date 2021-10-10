<?php

namespace App\Policies;

use App\Models\Transfer;
use App\Models\User;
use App\Traits\ModelToCompanyBelongingnessChecker;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransferPolicy
{
    use HandlesAuthorization, ModelToCompanyBelongingnessChecker;

    public function viewAny(User $user)
    {
        return $user->can('Read Transfer');
    }

    public function view(User $user, Transfer $transfer)
    {
        return $this->doesModelBelongToMyCompany($user, $transfer) && $user->can('Read Transfer');
    }

    public function create(User $user)
    {
        return $user->can('Create Transfer');
    }

    public function update(User $user, Transfer $transfer)
    {
        return $this->doesModelBelongToMyCompany($user, $transfer) && $user->can('Update Transfer');
    }

    public function delete(User $user, Transfer $transfer)
    {
        return $this->doesModelBelongToMyCompany($user, $transfer) && $user->can('Delete Transfer');
    }

    public function approve(User $user, Transfer $transfer)
    {
        return $this->doesModelBelongToMyCompany($user, $transfer) && $user->can('Approve Transfer');
    }

    public function transfer(User $user, Transfer $transfer)
    {
        if (!$transfer->isSubtracted() && !$user->hasWarehousePermission('subtract', $transfer->transferred_from)) {
            return false;
        }

        if ($transfer->isSubtracted() && !$user->hasWarehousePermission('add', $transfer->transferred_to)) {
            return false;
        }

        return $this->doesModelBelongToMyCompany($user, $transfer) && $user->can('Make Transfer');
    }
}
