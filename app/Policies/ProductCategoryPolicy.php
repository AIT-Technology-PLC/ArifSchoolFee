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
        //
    }

    public function view(User $user, ProductCategory $productCategory)
    {
        //
    }

    public function create(User $user)
    {
        //
    }

    public function update(User $user, ProductCategory $productCategory)
    {
        //
    }

    public function delete(User $user, ProductCategory $productCategory)
    {
        //
    }

    public function restore(User $user, ProductCategory $productCategory)
    {
        //
    }

    public function forceDelete(User $user, ProductCategory $productCategory)
    {
        //
    }
}
