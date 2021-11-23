<?php

namespace App\Policies;

use App\Models\Purchase;
use App\Models\User;
use App\Traits\VerifyModelIssuer;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchasePolicy
{
    use HandlesAuthorization, VerifyModelIssuer;

    public function viewAny(User $user)
    {
        return $user->can('Read Purchase');
    }

    public function view(User $user, Purchase $purchase)
    {
        return $this->isIssuedByMyCompany($user, $purchase) && $user->can('Read Purchase');
    }

    public function create(User $user)
    {
        return $user->can('Create Purchase');
    }

    public function update(User $user, Purchase $purchase)
    {
        return $this->isIssuedByMyCompany($user, $purchase, true) && $user->can('Update Purchase');
    }

    public function delete(User $user, Purchase $purchase)
    {
        return $this->isIssuedByMyCompany($user, $purchase, true) && $user->can('Delete Purchase');
    }

    public function close(User $user, Purchase $purchase)
    {
        return $this->isIssuedByMyCompany($user, $purchase) && $user->can('Close Purchase');
    }
}
