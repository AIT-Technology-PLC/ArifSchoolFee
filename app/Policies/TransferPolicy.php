<?php

namespace App\Policies;

use App\Models\Transfer;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransferPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Transfer $transfer)
    {
        $doesTransferBelongToMyCompany = $user->employee->company_id == $transfer->company_id;

        $canSeeTransfer = $doesTransferBelongToMyCompany;

        if ($canSeeTransfer) {
            return true;
        }

        return false;
    }

    public function create(User $user)
    {
        $isUserOperative = $user->employee->permission_id == 1 ||
        $user->employee->permission_id == 2 ||
        $user->employee->permission_id == 3;

        return $isUserOperative;
    }

    public function update(User $user, Transfer $transfer)
    {
        $isUserOperative = $user->employee->permission_id == 1 ||
        $user->employee->permission_id == 2 ||
        $user->employee->permission_id == 3;

        $doesTransferBelongToMyCompany = $user->employee->company_id == $transfer->company_id;

        $canUpdateTransfer = $isUserOperative && $doesTransferBelongToMyCompany;

        if ($canUpdateTransfer) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Transfer $transfer)
    {
        $isUserAdmin = $user->employee->permission_id == 1 || $user->employee->permission_id == 2;

        $doesTransferBelongToMyCompany = $user->employee->company_id == $transfer->company_id;

        $canDeleteTransfer = $isUserAdmin && $doesTransferBelongToMyCompany;

        if ($canDeleteTransfer) {
            return true;
        }

        return false;
    }
}
