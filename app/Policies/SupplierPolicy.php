<?php

namespace App\Policies;

use App\Models\Supplier;
use App\Models\User;
use App\Traits\VerifyModelIssuer;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupplierPolicy
{
    use HandlesAuthorization, VerifyModelIssuer;

    public function viewAny(User $user)
    {
        return $user->can('Read Supplier');
    }

    public function view(User $user, Supplier $supplier)
    {
        return $this->isIssuedByMyCompany($user, $supplier) && $user->can('Read Supplier');
    }

    public function create(User $user)
    {
        return $user->can('Create Supplier');
    }

    public function update(User $user, Supplier $supplier)
    {
        return $this->isIssuedByMyCompany($user, $supplier) && $user->can('Update Supplier');
    }

    public function delete(User $user, Supplier $supplier)
    {
        return $this->isIssuedByMyCompany($user, $supplier) && $user->can('Delete Supplier');
    }
}
