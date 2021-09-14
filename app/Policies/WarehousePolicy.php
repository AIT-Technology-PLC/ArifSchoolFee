<?php

namespace App\Policies;

use App\Models\Warehouse;
use App\Traits\ModelToCompanyBelongingnessChecker;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WarehousePolicy
{
    use HandlesAuthorization, ModelToCompanyBelongingnessChecker;

    public function viewAny(User $user)
    {
        return $user->can('Read Warehouse');
    }

    public function view(User $user, Warehouse $warehouse)
    {
        return $this->doesModelBelongToMyCompany($user, $warehouse) && $user->can('Read Warehouse');
    }

    public function create(User $user)
    {
        return $user->can('Create Warehouse');
    }

    public function update(User $user, Warehouse $warehouse)
    {
        return $this->doesModelBelongToMyCompany($user, $warehouse) && $user->can('Update Warehouse');
    }

    public function delete(User $user, Warehouse $warehouse)
    {
        return $this->doesModelBelongToMyCompany($user, $warehouse) && $user->can('Delete Warehouse');
    }
}
