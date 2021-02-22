<?php

namespace App\Policies;

use App\Models\Warehouse;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WarehousePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Warehouse');
    }

    public function view(User $user, Warehouse $warehouse)
    {
        $doesWarehouseBelongToMyCompany = $user->employee->company_id == $warehouse->company_id;

        return $doesWarehouseBelongToMyCompany && $user->can('Read Warehouse');
    }

    public function create(User $user)
    {
        return $user->can('Create Warehouse');
    }

    public function update(User $user, Warehouse $warehouse)
    {
        $doesWarehouseBelongToMyCompany = $user->employee->company_id == $warehouse->company_id;

        return $doesWarehouseBelongToMyCompany && $user->can('Update Warehouse');
    }

    public function delete(User $user, Warehouse $warehouse)
    {
        $doesWarehouseBelongToMyCompany = $user->employee->company_id == $warehouse->company_id;

        return $doesWarehouseBelongToMyCompany && $user->can('Delete Warehouse');
    }
}
