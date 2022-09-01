<?php

namespace App\Policies;

use App\Models\ExpenseCategory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpenseCategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Expense');
    }

    public function view(User $user, ExpenseCategory $expenseCategory)
    {
        return $user->can('Read Expense');
    }

    public function create(User $user)
    {
        return $user->can('Create Expense');
    }

    public function update(User $user, ExpenseCategory $expenseCategory)
    {
        return $user->can('Update Expense');
    }

    public function delete(User $user, ExpenseCategory $expenseCategory)
    {
        return $user->can('Delete Expense');
    }
}