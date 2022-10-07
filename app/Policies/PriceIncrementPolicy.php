<?php

namespace App\Policies;

use App\Models\PriceIncrement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PriceIncrementPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Price Increment');
    }

    public function view(User $user, PriceIncrement $priceIncrement)
    {
        return $user->can('Read Price Increment');
    }

    public function create(User $user)
    {
        return $user->can('Create Price Increment');
    }

    public function update(User $user, PriceIncrement $priceIncrement)
    {
        return $user->can('Update Price Increment');
    }

    public function delete(User $user, PriceIncrement $priceIncrement)
    {
        return $user->can('Delete Price Increment');
    }

    public function approve(User $user)
    {
        return $user->can('Approve Price Increment');
    }
}