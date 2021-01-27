<?php

namespace App\Policies;

use App\Models\PurchaseOrder;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchaseOrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, PurchaseOrder $purchaseOrder)
    {
        $doesPurchaseOrderBelongToMyCompany = $user->employee->company_id == $purchaseOrder->company_id;

        $canSeePurchaseOrder = $doesPurchaseOrderBelongToMyCompany;

        if ($canSeePurchaseOrder) {
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

    public function update(User $user, PurchaseOrder $purchaseOrder)
    {
        $isUserOperative = $user->employee->permission_id == 1 ||
        $user->employee->permission_id == 2 ||
        $user->employee->permission_id == 3;

        $doesPurchaseOrderBelongToMyCompany = $user->employee->company_id == $purchaseOrder->company_id;

        $canUpdatePurchaseOrder = $isUserOperative && $doesPurchaseOrderBelongToMyCompany;

        if ($canUpdatePurchaseOrder) {
            return true;
        }

        return false;
    }

    public function delete(User $user, PurchaseOrder $purchaseOrder)
    {
        $isUserAdmin = $user->employee->permission_id == 1 || $user->employee->permission_id == 2;

        $doesPurchaseOrderBelongToMyCompany = $user->employee->company_id == $purchaseOrder->company_id;

        $canDeletePurchaseOrder = $isUserAdmin && $doesPurchaseOrderBelongToMyCompany;

        if ($canDeletePurchaseOrder) {
            return true;
        }

        return false;
    }
}
