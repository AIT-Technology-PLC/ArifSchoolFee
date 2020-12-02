<?php

namespace App\Policies;

use App\Models\Warehouse;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WarehousePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Warehouse $warehouse)
    {
        $isUserOperative = true;

        $doesWarehouseBelongToMyCompany = $user->employee->company_id == $warehouse->company_id;

        $canSeeWarehouse = $isUserOperative && $doesWarehouseBelongToMyCompany;

        if ($canSeeWarehouse) {
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

    public function update(User $user, Warehouse $warehouse)
    {
        $isUserAdmin = $user->employee->permission_id == 1 || $user->employee->permission_id == 2;

        $doesWarehouseBelongToMyCompany = $user->employee->company_id == $warehouse->company_id;

        $canUpdateWarehouse = $isUserAdmin && $doesWarehouseBelongToMyCompany;

        if ($canUpdateWarehouse) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Warehouse $warehouse)
    {
        $isUserAdmin = $user->employee->permission_id == 1 || $user->employee->permission_id == 2;

        $doesWarehouseBelongToMyCompany = $user->employee->company_id == $warehouse->company_id;

        $canDeleteWarehouse = $isUserAdmin && $doesWarehouseBelongToMyCompany;

        if ($canDeleteWarehouse) {
            return true;
        }

        return false;
    }
}
