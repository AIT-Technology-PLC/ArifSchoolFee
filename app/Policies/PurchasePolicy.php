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
        return true;
    }

    public function view(User $user, Purchase $purchase)
    {
        $doesPurchaseBelongToMyCompany = $user->employee->company_id == $purchase->company_id;

        $canSeePurchase = $doesPurchaseBelongToMyCompany;

        if ($canSeePurchase) {
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

    public function update(User $user, Purchase $purchase)
    {
        $isUserOperative = $user->employee->permission_id == 1 ||
        $user->employee->permission_id == 2 ||
        $user->employee->permission_id == 3;

        $doesPurchaseBelongToMyCompany = $user->employee->company_id == $purchase->company_id;

        $canUpdatePurchase = $isUserOperative && $doesPurchaseBelongToMyCompany;

        if ($canUpdatePurchase) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Purchase $purchase)
    {
        $isUserAdmin = $user->employee->permission_id == 1 || $user->employee->permission_id == 2;

        $doesPurchaseBelongToMyCompany = $user->employee->company_id == $purchase->company_id;

        $canDeletePurchase = $isUserAdmin && $doesPurchaseBelongToMyCompany;

        if ($canDeletePurchase) {
            return true;
        }

        return false;
    }
}
