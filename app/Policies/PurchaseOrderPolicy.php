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
        return $user->can('Read PO');
    }

    public function view(User $user, PurchaseOrder $purchaseOrder)
    {
        $doesPurchaseOrderBelongToMyCompany = $user->employee->company_id == $purchaseOrder->company_id;

        return $doesPurchaseOrderBelongToMyCompany && $user->can('Read PO');
    }

    public function create(User $user)
    {
        return $user->can('Create PO');
    }

    public function update(User $user, PurchaseOrder $purchaseOrder)
    {
        $doesPurchaseOrderBelongToMyCompany = $user->employee->company_id == $purchaseOrder->company_id;

        return $doesPurchaseOrderBelongToMyCompany && $user->can('Update PO');
    }

    public function delete(User $user, PurchaseOrder $purchaseOrder)
    {
        $doesPurchaseOrderBelongToMyCompany = $user->employee->company_id == $purchaseOrder->company_id;

        return $doesPurchaseOrderBelongToMyCompany && $user->can('Delete PO');
    }
}
