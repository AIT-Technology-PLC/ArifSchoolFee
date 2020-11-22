<?php

namespace App\Policies;

use App\Models\Supplier;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupplierPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        //
    }

    public function view(User $user, Supplier $supplier)
    {
        //
    }

    public function create(User $user)
    {
        //
    }

    public function update(User $user, Supplier $supplier)
    {
        //
    }

    public function delete(User $user, Supplier $supplier)
    {
        //
    }
}
