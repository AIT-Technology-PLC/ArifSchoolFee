<?php

namespace App\Policies;

use App\Models\Sale;
use App\Traits\ModelToCompanyBelongingnessChecker;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalePolicy
{
    use HandlesAuthorization, ModelToCompanyBelongingnessChecker;

    public function viewAny(User $user)
    {
        return $user->can('Read Sale');
    }

    public function view(User $user, Sale $sale)
    {
        return $this->doesModelBelongToMyCompany($user, $sale) && $user->can('Read Sale');
    }

    public function create(User $user)
    {
        return $user->can('Create Sale');
    }

    public function update(User $user, Sale $sale)
    {
        return $this->doesModelBelongToMyCompany($user, $sale) && $user->can('Update Sale');
    }

    public function delete(User $user, Sale $sale)
    {
        return $this->doesModelBelongToMyCompany($user, $sale) && $user->can('Delete Sale');
    }
}
