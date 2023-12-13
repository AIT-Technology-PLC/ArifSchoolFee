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
        return $user->can('Read Sale');
    }

    public function create(User $user)
    {
        return $user->can('Create Sale');
    }

    public function update(User $user, Sale $sale)
    {
        $permission = 'Update Sale';

        if ($sale->isApproved() && $sale->company->canSaleSubtract() && !$sale->isSubtracted()) {
            $permission = 'Update Approved Sale';
        }

        return $this->isIssuedByMyBranch($user, $sale) && $user->can($permission);
    }

    public function delete(User $user, Sale $sale)
    {
        return $this->isIssuedByMyBranch($user, $sale) && $user->can('Delete Sale');
    }

    public function approve(User $user, Sale $sale)
    {
        return $user->can('Approve Sale');
    }

    public function cancel(User $user, Sale $sale)
    {
        return $user->can('Cancel Sale');
    }

    public function subtract(User $user, Sale $sale)
    {
        return $user->can('Subtract Sale');
    }

    public function convertToCredit(User $user, Sale $sale)
    {
        return $user->can('Convert To Credit');
    }
}
