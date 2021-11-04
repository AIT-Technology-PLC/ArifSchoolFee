<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;
use App\Traits\VerifyModelIssuer;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalePolicy
{
    use HandlesAuthorization, VerifyModelIssuer;

    public function viewAny(User $user)
    {
        return $user->can('Read Sale');
    }

    public function view(User $user, Sale $sale)
    {
        return $this->isIssuedByMyCompany($user, $sale) && $user->can('Read Sale');
    }

    public function create(User $user)
    {
        return $user->can('Create Sale');
    }

    public function update(User $user, Sale $sale)
    {
        return $this->isIssuedByMyCompany($user, $sale) && $user->can('Update Sale');
    }

    public function delete(User $user, Sale $sale)
    {
        return $this->isIssuedByMyCompany($user, $sale, true) && $user->can('Delete Sale');
    }
}
