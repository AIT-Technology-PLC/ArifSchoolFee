<?php

namespace App\Policies;

use App\Models\Sale;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Sale');
    }

    public function view(User $user, Sale $sale)
    {
        $doesSaleBelongToMyCompany = $user->employee->company_id == $sale->company_id;

        return $doesSaleBelongToMyCompany && $user->can('Read Sale');
    }

    public function create(User $user)
    {
        return $user->can('Create Sale');
    }

    public function update(User $user, Sale $sale)
    {
        $doesSaleBelongToMyCompany = $user->employee->company_id == $sale->company_id;

        return $doesSaleBelongToMyCompany && $user->can('Update Sale');
    }

    public function delete(User $user, Sale $sale)
    {
        $doesSaleBelongToMyCompany = $user->employee->company_id == $sale->company_id;

        return $doesSaleBelongToMyCompany && $user->can('Delete Sale');
    }
}
