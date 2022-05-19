<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Warehouse;
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
        return $user->can('Read Warehouse');
    }

    public function create(User $user)
    {
        return $user->can('Create Warehouse');
    }

    public function update(User $user, Warehouse $warehouse)
    {
        return $user->can('Update Warehouse');
    }

    public function delete(User $user, Warehouse $warehouse)
    {
        return $user->can('Delete Warehouse');
    }

    public function import(User $user)
    {
        return $user->can('Import Warehouse');
    }
}