<?php

namespace App\Policies;

use App\Models\Price;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PricePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Price');
    }

    public function view(User $user, Price $price)
    {
        return $user->can('Read Price');
    }

    public function create(User $user)
    {
        return $user->can('Create Price');
    }

    public function update(User $user, Price $price)
    {
        return $user->can('Update Price');
    }

    public function delete(User $user, Price $price)
    {
        return $user->can('Delete Price');
    }

    public function import(User $user)
    {
        return $user->can('Import Price');
    }
}
