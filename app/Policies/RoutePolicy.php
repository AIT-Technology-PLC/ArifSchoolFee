<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Route;
use Illuminate\Auth\Access\HandlesAuthorization;

class RoutePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Route');
    }

    public function view(User $user, Route $route)
    {
        return $user->can('Read Route');
    }

    public function create(User $user)
    {
        return $user->can('Create Route');
    }

    public function update(User $user, Route $route)
    {
        return $user->can('Update Route');
    }

    public function delete(User $user, Route $route)
    {
        return $user->can('Delete Route');
    }

    public function import(User $user)
    {
        return $user->can('Import Route');
    }
}
