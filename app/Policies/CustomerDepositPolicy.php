<?php

namespace App\Policies;

use App\Models\CustomerDeposit;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerDepositPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Customer Deposit');
    }

    public function view(User $user, CustomerDeposit $customerDeposit)
    {
        return $user->can('Read Customer Deposit');
    }

    public function create(User $user)
    {
        return $user->can('Create Customer Deposit');
    }

    public function update(User $user, CustomerDeposit $customerDeposit)
    {
        return $user->can('Update Customer Deposit');
    }

    public function delete(User $user, CustomerDeposit $customerDeposit)
    {
        return $user->can('Update Customer Deposit');
    }

    public function approve(User $user, CustomerDeposit $customerDeposit)
    {
        return $user->can('Approve Customer Deposit');
    }
}