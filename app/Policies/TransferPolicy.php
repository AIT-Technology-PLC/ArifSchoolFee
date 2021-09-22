<?php

namespace App\Policies;

use App\Models\Transfer;
use App\Traits\ModelToCompanyBelongingnessChecker;
use App\Models\User;
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
        return $this->doesModelBelongToMyCompany($user, $transfer) && $user->can('Make Transfer');
    }
}
