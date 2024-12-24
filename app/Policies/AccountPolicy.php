<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Account');
    }

    public function view(User $user, Account $account)
    {
        return $user->can('Read Account');
    }

    public function create(User $user)
    {
        return $user->can('Create Account');
    }

    public function update(User $user, Account $account)
    {
        return $user->can('Update Account');
    }

    public function delete(User $user, Account $account)
    {
        return $user->can('Delete Account');
    }
}
