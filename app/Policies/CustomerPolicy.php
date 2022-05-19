<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Customer');
    }

    public function view(User $user, Customer $customer)
    {
        return $user->can('Read Customer');
    }

    public function create(User $user)
    {
        return $user->can('Create Customer');
    }

    public function update(User $user, Customer $customer)
    {
        return $user->can('Update Customer');
    }

    public function delete(User $user, Customer $customer)
    {
        return $user->can('Delete Customer');
    }

    public function import(User $user)
    {
        return $user->can('Import Customer');
    }
}