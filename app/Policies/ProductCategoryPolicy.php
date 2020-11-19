<?php

namespace App\Policies;

use App\Models\ProductCategory;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductCategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, ProductCategory $category)
    {
        $isUserOperative = true;

        $doesCategoryBelongToMyCompany = $user->employee->company_id == $category->company_id;

        $canSeeCategory = $isUserOperative && $doesCategoryBelongToMyCompany;

        if ($canSeeCategory) {
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

    public function update(User $user, ProductCategory $category)
    {
        $isUserOperative = $user->employee->permission_id == 1 ||
        $user->employee->permission_id == 2 ||
        $user->employee->permission_id == 3;

        $doesCategoryBelongToMyCompany = $user->employee->company_id == $category->company_id;

        $canUpdateCategory = $isUserOperative && $doesCategoryBelongToMyCompany;

        if ($canUpdateCategory) {
            return true;
        }

        return false;
    }

    public function delete(User $user, ProductCategory $category)
    {
        $isUserAdmin = $user->employee->permission_id == 1 || $user->employee->permission_id == 2;

        $doesCategoryBelongToMyCompany = $user->employee->company_id == $category->company_id;

        $canDeleteCategory = $isUserAdmin && $doesCategoryBelongToMyCompany;

        if ($canDeleteCategory) {
            return true;
        }

        return false;
    }
}
