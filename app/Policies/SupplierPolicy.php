<?php

namespace App\Policies;

use App\Models\Supplier;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupplierPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Supplier $supplier)
    {
        $doesSupplierBelongToMyCompany = $user->employee->company_id == $supplier->company_id;

        $canSeeSupplier = $doesSupplierBelongToMyCompany;

        if ($canSeeSupplier) {
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

    public function update(User $user, Supplier $supplier)
    {
        $isUserOperative = $user->employee->permission_id == 1 ||
        $user->employee->permission_id == 2 ||
        $user->employee->permission_id == 3;

        $doesSupplierBelongToMyCompany = $user->employee->company_id == $supplier->company_id;

        $canUpdateSupplier = $isUserOperative && $doesSupplierBelongToMyCompany;

        if ($canUpdateSupplier) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Supplier $supplier)
    {
        $isUserAdmin = $user->employee->permission_id == 1 || $user->employee->permission_id == 2;

        $doesSupplierBelongToMyCompany = $user->employee->company_id == $supplier->company_id;

        $canDeleteSupplier = $isUserAdmin && $doesSupplierBelongToMyCompany;

        if ($canDeleteSupplier) {
            return true;
        }

        return false;
    }
}
