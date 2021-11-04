<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use App\Traits\VerifyModelIssuer;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization, VerifyModelIssuer;

    public function viewAny(User $user)
    {
        return $user->can('Read Product');
    }

    public function view(User $user, Product $product)
    {
        return $this->doesModelBelongToMyCompany($user, $product) & $user->can('Read Product');
    }

    public function create(User $user)
    {
        return $user->can('Create Product');
    }

    public function update(User $user, Product $product)
    {
        return $this->doesModelBelongToMyCompany($user, $product) && $user->can('Update Product');
    }

    public function delete(User $user, Product $product)
    {
        return $this->doesModelBelongToMyCompany($user, $product) && $user->can('Delete Product');
    }
}
