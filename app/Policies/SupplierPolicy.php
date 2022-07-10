<?php

namespace App\Policies;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupplierPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Supplier');
    }

    public function view(User $user, Supplier $supplier)
    {
        return $user->can('Read Supplier');
    }

    public function create(User $user)
    {
        return $user->can('Create Supplier');
    }

    public function update(User $user, Supplier $supplier)
    {
        return $user->can('Update Supplier');
    }

    public function delete(User $user, Supplier $supplier)
    {
        return $user->can('Delete Supplier');
    }

    public function import(User $user)
    {
        return $user->can('Import Supplier');
    }
}
