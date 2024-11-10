<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Auth\Access\HandlesAuthorization;

class VehiclePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Vehicle');
    }

    public function view(User $user, Vehicle $vehicle)
    {
        return $user->can('Read Vehicle');
    }

    public function create(User $user)
    {
        return $user->can('Create Vehicle');
    }

    public function update(User $user, Vehicle $vehicle)
    {
        return $user->can('Update Vehicle');
    }

    public function delete(User $user, Vehicle $vehicle)
    {
        return $user->can('Delete Vehicle');
    }

    public function import(User $user)
    {
        return $user->can('Import Vehicle');
    }
}
