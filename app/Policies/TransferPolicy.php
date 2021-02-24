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
        return $user->can('Read Transfer');
    }

    public function view(User $user, Transfer $transfer)
    {
        $doesTransferBelongToMyCompany = $user->employee->company_id == $transfer->company_id;

        return $doesTransferBelongToMyCompany && $user->can('Read Transfer');
    }

    public function create(User $user)
    {
        return $user->can('Create Transfer');
    }

    public function update(User $user, Transfer $transfer)
    {
        $doesTransferBelongToMyCompany = $user->employee->company_id == $transfer->company_id;

        return $doesTransferBelongToMyCompany && $user->can('Update Transfer');
    }

    public function delete(User $user, Transfer $transfer)
    {
        $doesTransferBelongToMyCompany = $user->employee->company_id == $transfer->company_id;

        return $doesTransferBelongToMyCompany && $user->can('Delete Transfer');
    }

    public function approve(User $user, Transfer $transfer)
    {
        $doesTransferBelongToMyCompany = $user->employee->company_id == $transfer->company_id;

        return $doesTransferBelongToMyCompany && $user->can('Approve Transfer');
    }
}
