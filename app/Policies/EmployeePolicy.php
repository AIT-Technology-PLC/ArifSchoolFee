<?php

namespace App\Policies;

use App\Models\Employee;
use App\Traits\ModelToCompanyBelongingnessChecker;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy
{
    use HandlesAuthorization, ModelToCompanyBelongingnessChecker;

    public function viewAny(User $user)
    {
        return $user->can('Read Employee');
    }

    public function create(User $user)
    {
        return $user->can('Create Employee');
    }

    public function view(User $user, Employee $employee)
    {
        if ($user->employee->id == $employee->id) {
            return true;
        }

        return $this->doesModelBelongToMyCompany($user, $employee) && $user->can('Read Employee');
    }

    public function update(User $user, Employee $employee)
    {
        if (!$this->doesModelBelongToMyCompany($user, $employee) || !$user->can('Update Employee')) {
            return false;
        }

        if ($user->id == $employee->user->id && $user->hasRole('System Manager')) {
            return true;
        }

        if ($user->id != $employee->user->id) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Employee $employee)
    {
        return $this->doesModelBelongToMyCompany($user, $employee) && $user->can('Delete Employee');
    }

}
