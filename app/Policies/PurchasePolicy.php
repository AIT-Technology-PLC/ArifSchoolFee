<?php

namespace App\Policies;

use App\Models\Purchase;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchasePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Purchase');
    }

    public function view(User $user, Purchase $purchase)
    {
        $doesPurchaseBelongToMyCompany = $user->employee->company_id == $purchase->company_id;

        return $doesPurchaseBelongToMyCompany && $user->can('Read Purchase');
    }

    public function create(User $user)
    {
        return $user->can('Create Purchase');
    }

    public function update(User $user, Purchase $purchase)
    {
        $doesPurchaseBelongToMyCompany = $user->employee->company_id == $purchase->company_id;

        return $doesPurchaseBelongToMyCompany && $user->can('Update Purchase');
    }

    public function delete(User $user, Purchase $purchase)
    {
        $doesPurchaseBelongToMyCompany = $user->employee->company_id == $purchase->company_id;

        return $doesPurchaseBelongToMyCompany && $user->can('Delete Purchase');
    }
}
