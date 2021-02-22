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
        $doesCategoryBelongToMyCompany = $user->employee->company_id == $category->company_id;

        return $doesCategoryBelongToMyCompany;
    }

    public function create(User $user)
    {
        return $user->can('Create Product');
    }

    public function update(User $user, ProductCategory $category)
    {
        $doesCategoryBelongToMyCompany = $user->employee->company_id == $category->company_id;

        return $doesCategoryBelongToMyCompany && $user->can('Update Product');
    }

    public function delete(User $user, ProductCategory $category)
    {
        $doesCategoryBelongToMyCompany = $user->employee->company_id == $category->company_id;

        return $doesCategoryBelongToMyCompany && $user->can('Read Product');
    }
}
