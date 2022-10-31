<?php

namespace App\Policies;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BrandPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Brand');
    }

    public function view(User $user, Brand $brand)
    {
        return $user->can('Read Brand');
    }

    public function create(User $user)
    {
        return $user->can('Create Brand');
    }

    public function update(User $user, Brand $brand)
    {
        return $user->can('Update Brand');
    }

    public function delete(User $user, Brand $brand)
    {
        return $user->can('Delete Brand');
    }

    public function import(User $user)
    {
        return $user->can('Import Brand');
    }
}
