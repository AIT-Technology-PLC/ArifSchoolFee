<?php

namespace App\Policies;

use App\Models\Payroll;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PayrollPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Payroll');
    }

    public function view(User $user, Payroll $payroll)
    {
        return $user->can('Read Payroll');
    }

    public function create(User $user)
    {
        return $user->can('Create Payroll');
    }

    public function update(User $user, Payroll $payroll)
    {
        return $user->can('Update Payroll');
    }

    public function delete(User $user, Payroll $payroll)
    {
        return $user->can('Delete Payroll');
    }

    public function approve(User $user, Payroll $payroll)
    {
        return $user->can('Approve Payroll');
    }

    public function cancel(User $user, Payroll $payroll)
    {
        return $user->can('Pay Payroll');
    }
}