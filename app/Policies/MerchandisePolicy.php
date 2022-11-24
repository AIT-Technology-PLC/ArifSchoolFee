<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MerchandisePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->canAny([
            'Read Available Inventory',
            'Read Reserved Inventory',
            'Read Work In Process Inventory',
            'Read On Hand Inventory',
            'Read Out Of Stock Inventory',
            'Read Expired Inventory',
        ]);
    }

    public function available(User $user)
    {
        return $user->can('Read Available Inventory');
    }

    public function reserved(User $user)
    {
        return $user->can('Read Reserved Inventory');
    }

    public function wip(User $user)
    {
        return $user->can('Read Work In Process Inventory');
    }

    public function onHand(User $user)
    {
        return $user->can('Read On Hand Inventory');
    }

    public function outOfStock(User $user)
    {
        return $user->can('Read Out Of Stock Inventory');
    }

    public function expired(User $user)
    {
        return $user->can('Read Expired Inventory');
    }
}