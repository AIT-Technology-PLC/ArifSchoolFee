<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpensePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Expense');
    }

    public function view(User $user, Expense $expense)
    {
        return $user->can('Read Expense');
    }

    public function create(User $user)
    {
        return $user->can('Create Expense');
    }

    public function update(User $user, Expense $expense)
    {
        return $user->can('Update Expense');
    }

    public function delete(User $user, Expense $expense)
    {
        return $user->can('Delete Expense');
    }

    public function approve(User $user, Expense $expense)
    {
        return $user->can('Approve Expense');
    }
}