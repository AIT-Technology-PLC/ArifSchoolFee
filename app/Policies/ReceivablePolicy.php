<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReceivablePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Receivable');
    }

    public function view(User $user, Customer $customer)
    {
        return $user->can('Read Receivable');
    }
}
