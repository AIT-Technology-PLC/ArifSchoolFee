<?php

namespace App\Policies;

use App\Models\Supplier;
use App\Traits\ModelToCompanyBelongingnessChecker;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupplierPolicy
{
    use HandlesAuthorization, ModelToCompanyBelongingnessChecker;

    public function viewAny(User $user)
    {
        return $user->can('Read Supplier');
    }

    public function view(User $user, Supplier $supplier)
    {
        return $this->doesModelBelongToMyCompany($user, $supplier) && $user->can('Read Supplier');
    }

    public function create(User $user)
    {
        return $user->can('Create Supplier');
    }

    public function update(User $user, Supplier $supplier)
    {
        return $this->doesModelBelongToMyCompany($user, $supplier) && $user->can('Update Supplier');
    }

    public function delete(User $user, Supplier $supplier)
    {
        return $this->doesModelBelongToMyCompany($user, $supplier) && $user->can('Delete Supplier');
    }
}
