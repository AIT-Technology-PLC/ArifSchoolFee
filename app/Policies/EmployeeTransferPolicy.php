<?php

namespace App\Policies;

use App\Models\EmployeeTransfer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeTransferPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Employee Transfer');
    }

    public function view(User $user, EmployeeTransfer $employeeTransfer)
    {
        return $user->can('Read Employee Transfer');
    }

    public function create(User $user)
    {
        return $user->can('Create Employee Transfer');
    }

    public function update(User $user)
    {
        return $user->can('Update Employee Transfer');
    }

    public function delete(User $user, EmployeeTransfer $employeeTransfer)
    {
        return $user->can('Delete Employee Transfer');
    }

    public function approve(User $user, EmployeeTransfer $employeeTransfer)
    {
        return $user->can('Approve Employee Transfer');
    }
}
