<?php

namespace App\Policies;

use App\Models\Product;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Product $product)
    {
        $isUserOperative = true;

        $doesProductBelongToMyCompany = $user->employee->company_id == $product->company_id;

        $canSeeProduct = $isUserOperative && $doesProductBelongToMyCompany;

        if ($canSeeProduct) {
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

    public function update(User $user, Product $product)
    {
        $isUserOperative = $user->employee->permission_id == 1 ||
        $user->employee->permission_id == 2 ||
        $user->employee->permission_id == 3;

        $doesProductBelongToMyCompany = $user->employee->company_id == $product->company_id;

        $canUpdateProduct = $isUserOperative && $doesProductBelongToMyCompany;

        if ($canUpdateProduct) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Product $product)
    {
        $isUserAdmin = $user->employee->permission_id == 1 || $user->employee->permission_id == 2;

        $doesProductBelongToMyCompany = $user->employee->company_id == $product->company_id;

        $canDeleteProduct = $isUserAdmin && $doesProductBelongToMyCompany;

        if ($canDeleteProduct) {
            return true;
        }

        return false;
    }
}
