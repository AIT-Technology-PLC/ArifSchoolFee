<?php

namespace App\Policies;

use App\Models\ExpenseClaim;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpenseClaimPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read  Expense Claim');
    }

    public function view(User $user, ExpenseClaim $expenseClaim)
    {
        return $user->can('Read  Expense Claim');
    }

    public function create(User $user)
    {
        return $user->can('Create  Expense Claim');
    }

    public function update(User $user, ExpenseClaim $expenseClaim)
    {
        return $user->can('Update  Expense Claim');
    }

    public function delete(User $user, ExpenseClaim $expenseClaim)
    {
        return $user->can('Delete  Expense Claim');
    }

    public function approve(User $user, ExpenseClaim $expenseClaim)
    {
        return $user->can('Approve  Expense Claim');
    }

    public function reject(User $user, ExpenseClaim $expenseClaim)
    {
        return $user->can('Reject Expense Claim');
    }
}