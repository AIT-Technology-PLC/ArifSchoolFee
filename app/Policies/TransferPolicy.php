<?php

namespace App\Policies;

use App\Models\Transfer;
use App\Models\User;
use App\Traits\VerifyModelIssuer;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransferPolicy
{
    use HandlesAuthorization, VerifyModelIssuer;

    public function viewAny(User $user)
    {
        return $user->can('Read Transfer');
    }

    public function view(User $user, Transfer $transfer)
    {
        if (!$this->isIssuedByMyCompany($user, $transfer) || !$user->can('Read Transfer')) {
            return false;
        }

        return $user->hasWarehousePermission('transactions', $transfer->transferred_from) ||
        $user->hasWarehousePermission('transactions', $transfer->transferred_to);
    }

    public function create(User $user)
    {
        return $user->can('Create Transfer');
    }

    public function update(User $user, Transfer $transfer)
    {
        return $this->isIssuedByMyCompany($user, $transfer, true) && $user->can('Update Transfer');
    }

    public function delete(User $user, Transfer $transfer)
    {
        return $this->isIssuedByMyCompany($user, $transfer, true) && $user->can('Delete Transfer');
    }

    public function approve(User $user, Transfer $transfer)
    {
        return $this->isIssuedByMyCompany($user, $transfer) && $user->can('Approve Transfer');
    }

    public function transfer(User $user, Transfer $transfer)
    {
        if (!$transfer->isSubtracted() && !$user->hasWarehousePermission('subtract', $transfer->transferred_from)) {
            return false;
        }

        if ($transfer->isSubtracted() && !$user->hasWarehousePermission('add', $transfer->transferred_to)) {
            return false;
        }

        return $this->isIssuedByMyCompany($user, $transfer) && $user->can('Make Transfer');
    }
}
