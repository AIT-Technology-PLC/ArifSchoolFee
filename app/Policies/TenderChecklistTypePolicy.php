<?php

namespace App\Policies;

use App\Models\TenderChecklistType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TenderChecklistTypePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Tender');
    }

    public function view(User $user, TenderChecklistType $tenderChecklistType)
    {
        return $user->can('Read Tender');
    }

    public function create(User $user)
    {
        return $user->can('Create Tender');
    }

    public function update(User $user, TenderChecklistType $tenderChecklistType)
    {
        return $user->can('Update Tender');
    }

    public function delete(User $user, TenderChecklistType $tenderChecklistType)
    {
        return $user->can('Delete Tender');
    }
}
