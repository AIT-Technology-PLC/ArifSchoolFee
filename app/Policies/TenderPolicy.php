<?php

namespace App\Policies;

use App\Models\Tender;
use App\Models\User;
use App\Traits\VerifyModelIssuer;
use Illuminate\Auth\Access\HandlesAuthorization;

class TenderPolicy
{
    use HandlesAuthorization, VerifyModelIssuer;

    public function viewAny(User $user)
    {
        return $user->can('Read Tender');
    }

    public function view(User $user, Tender $tender)
    {
        return $user->can('Read Tender');
    }

    public function create(User $user)
    {
        return $user->can('Create Tender');
    }

    public function update(User $user, Tender $tender)
    {
        return $this->isIssuedByMyBranch($user, $tender) && $user->can('Update Tender');
    }

    public function delete(User $user, Tender $tender)
    {
        return $this->isIssuedByMyBranch($user, $tender) && $user->can('Delete Tender');
    }

    public function assign(User $user, Tender $tender)
    {
        return $user->can('Assign Tender Checklists');
    }

    public function import(User $user)
    {
        return $user->can('Import Tender');
    }
}