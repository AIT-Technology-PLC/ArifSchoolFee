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
        return $user->can('Read Branch');
    }

    public function view(User $user, Warehouse $branch)
    {
        return $user->can('Read Branch');
    }

    public function create(User $user)
    {
        return $user->can('Create Branch');
    }

    public function update(User $user, Warehouse $branch)
    {
        return $user->can('Update Branch');
    }

    public function import(User $user)
    {
        return $user->can('Import Branch');
    }
}
