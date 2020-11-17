<?php

namespace App\Policies;

use App\Models\Employee;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Str;

class EmployeePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->employee->permission_id == 1;
    }

    public function create(User $user)
    {
        return $user->employee->permission_id == 1;
    }

    public function view(User $user, Employee $employee)
    {
        $doesAdminAndEmployeeBelongToSameCompany = $user->employee->company_id == $employee->company_id;

        $isUserAdmin = $user->employee->permission_id == 1;

        $isItMyProfie = $user->employee->id == $employee->id;

        $canSeeProfile = $isItMyProfie || ($isUserAdmin && $doesAdminAndEmployeeBelongToSameCompany);

        if ($canSeeProfile) {
            return true;
        }

        return false;
    }

    public function update(User $user, Employee $employee)
    {
        $doesAdminAndEmployeeBelongToSameCompany = $user->employee->company_id == $employee->company_id;

        $isUserAdmin = $user->employee->permission_id == 1;

        $canEditEmployeeData = $isUserAdmin && $doesAdminAndEmployeeBelongToSameCompany;

        if ($canEditEmployeeData) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Employee $employee)
    {
        $doesAdminAndEmployeeBelongToSameCompany = $user->employee->company_id == $employee->company_id;

        $isUserAdmin = $user->employee->permission_id == 1;

        $canEditEmployeeData = $isUserAdmin && $doesAdminAndEmployeeBelongToSameCompany;

        if ($canEditEmployeeData) {
            return true;
        }

        return false;
    }

}
