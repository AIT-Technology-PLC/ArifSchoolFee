<?php

namespace App\Policies;

use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductCategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Product');
    }

    public function view(User $user, ProductCategory $category)
    {
        return $user->can('Read Product');
    }

    public function create(User $user)
    {
        return $user->can('Create Product');
    }

    public function update(User $user, ProductCategory $category)
    {
        return $user->can('Update Product');
    }

    public function delete(User $user, ProductCategory $category)
    {
        return $user->can('Delete Product');
    }
}
