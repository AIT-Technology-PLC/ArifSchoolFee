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
        return $user->can('Read Transfer');
    }

    public function create(User $user)
    {
        return $user->can('Create Transfer');
    }

    public function update(User $user, Transfer $transfer)
    {
        $permission = 'Update Transfer';

        if ($transfer->isApproved() && !$transfer->isSubtracted()) {
            $permission = 'Update Approved Transfer';
        }

        return $this->isIssuedByMyBranch($user, $transfer) && $user->can($permission);
    }

    public function delete(User $user, Transfer $transfer)
    {
        return $this->isIssuedByMyBranch($user, $transfer) && $user->can('Delete Transfer');
    }

    public function approve(User $user, Transfer $transfer)
    {
        return $user->can('Approve Transfer');
    }

    public function transfer(User $user, Transfer $transfer)
    {
        if (!$transfer->isSubtracted() && !$user->hasWarehousePermission('subtract', $transfer->transferred_from)) {
            return false;
        }

        if ($transfer->isSubtracted() && !$user->hasWarehousePermission('add', $transfer->transferred_to)) {
            return false;
        }

        return $user->can('Make Transfer');
    }

    public function close(User $user, Transfer $transfer)
    {
        return $user->can('Close Transfer');
    }
}
