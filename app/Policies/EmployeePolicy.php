<?php

namespace App\Policies;

use App\Models\Employee;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy
{
    use HandlesAuthorization;

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
        $doesAdminAndEmployeeBelongToSameCompany = $user->employee->company_id == $employee->company_id;

        $isItMyProfie = $user->employee->id == $employee->id;

        return $isItMyProfie || ($user->can('Read Employee') && $doesAdminAndEmployeeBelongToSameCompany);
    }

    public function update(User $user, Employee $employee)
    {
        $doesAdminAndEmployeeBelongToSameCompany = $user->employee->company_id == $employee->company_id;

        if ($doesAdminAndEmployeeBelongToSameCompany && $user->hasRole('System Manager')) {
            return true;
        }

        return $doesAdminAndEmployeeBelongToSameCompany && $user->id != $employee->user->id && $user->can('Update Employee');
    }

    public function delete(User $user, Employee $employee)
    {
        $doesAdminAndEmployeeBelongToSameCompany = $user->employee->company_id == $employee->company_id;

        return $doesAdminAndEmployeeBelongToSameCompany && $user->can('Delete Employee');
    }

}
