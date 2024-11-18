<?php

namespace App\Policies;

use App\Models\FeeMaster;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FeeMasterPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Fee Master');
    }

    public function view(User $user, FeeMaster $feeMaster)
    {
        return $user->can('Read Fee Master');
    }

    public function create(User $user)
    {
        return $user->can('Create Fee Master');
    }

    public function update(User $user, FeeMaster $feeMaster)
    {
        return $user->can('Update Fee Master');
    }

    public function delete(User $user, FeeMaster $feeMaster)
    {
        return $user->can('Delete Fee Master');
    }

    public function import(User $user)
    {
        return $user->can('Import Fee Master');
    }

    public function assign(User $user)
    {
        return $user->can('Assign Fee Master');
    }
}
