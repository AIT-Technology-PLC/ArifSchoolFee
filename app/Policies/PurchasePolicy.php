<?php

namespace App\Policies;

use App\Models\Purchase;
use App\Traits\ModelToCompanyBelongingnessChecker;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchasePolicy
{
    use HandlesAuthorization, ModelToCompanyBelongingnessChecker;

    public function viewAny(User $user)
    {
        return $user->can('Read Purchase');
    }

    public function view(User $user, Purchase $purchase)
    {
        return $this->doesModelBelongToMyCompany($user, $purchase) && $user->can('Read Purchase');
    }

    public function create(User $user)
    {
        return $user->can('Create Purchase');
    }

    public function update(User $user, Purchase $purchase)
    {
        return $this->doesModelBelongToMyCompany($user, $purchase) && $user->can('Update Purchase');
    }

    public function delete(User $user, Purchase $purchase)
    {
        return $this->doesModelBelongToMyCompany($user, $purchase) && $user->can('Delete Purchase');
    }
}
