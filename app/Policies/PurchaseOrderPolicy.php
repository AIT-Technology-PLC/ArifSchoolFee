<?php

namespace App\Policies;

use App\Models\PurchaseOrder;
use App\Traits\ModelToCompanyBelongingnessChecker;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchaseOrderPolicy
{
    use HandlesAuthorization, ModelToCompanyBelongingnessChecker;

    public function viewAny(User $user)
    {
        return $user->can('Read PO');
    }

    public function view(User $user, PurchaseOrder $purchaseOrder)
    {
        return $this->doesModelBelongToMyCompany($user, $purchaseOrder) && $user->can('Read PO');
    }

    public function create(User $user)
    {
        return $user->can('Create PO');
    }

    public function update(User $user, PurchaseOrder $purchaseOrder)
    {
        return $this->doesModelBelongToMyCompany($user, $purchaseOrder) && $user->can('Update PO');
    }

    public function delete(User $user, PurchaseOrder $purchaseOrder)
    {
        return $this->doesModelBelongToMyCompany($user, $purchaseOrder) && $user->can('Delete PO');
    }
}
