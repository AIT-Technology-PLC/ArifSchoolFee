<?php

namespace App\Policies;

use App\Models\ProductCategory;
use App\Traits\ModelToCompanyBelongingnessChecker;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductCategoryPolicy
{
    use HandlesAuthorization, ModelToCompanyBelongingnessChecker;

    public function viewAny(User $user)
    {
        return $user->can('Read Product');
    }

    public function view(User $user, ProductCategory $category)
    {
        return $this->doesModelBelongToMyCompany($user, $category) && $user->can('Read Product');
    }

    public function create(User $user)
    {
        return $user->can('Create Product');
    }

    public function update(User $user, ProductCategory $category)
    {
        return $this->doesModelBelongToMyCompany($user, $category) && $user->can('Update Product');
    }

    public function delete(User $user, ProductCategory $category)
    {
        return $this->doesModelBelongToMyCompany($user, $category) && $user->can('Delete Product');
    }
}
