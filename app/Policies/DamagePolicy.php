<?php

namespace App\Policies;

use App\Models\Damage;
use App\Models\User;
use App\Traits\VerifyModelIssuer;
use Illuminate\Auth\Access\HandlesAuthorization;

class DamagePolicy
{
    use HandlesAuthorization, VerifyModelIssuer;

    public function viewAny(User $user)
    {
        return $user->can('Read Damage');
    }

    public function view(User $user, Damage $damage)
    {
        return $this->isIssuedByMyCompany($user, $damage) && $user->can('Read Damage');
    }

    public function create(User $user)
    {
        return $user->can('Create Damage');
    }

    public function update(User $user, Damage $damage)
    {
        return $this->isIssuedByMyCompany($user, $damage) && $user->can('Update Damage');
    }

    public function delete(User $user, Damage $damage)
    {
        return $this->isIssuedByMyCompany($user, $damage, true) && $user->can('Delete Damage');
    }

    public function approve(User $user, Damage $damage)
    {
        return $this->isIssuedByMyCompany($user, $damage) && $user->can('Approve Damage');
    }

    public function subtract(User $user, Damage $damage)
    {
        return $this->isIssuedByMyCompany($user, $damage) && $user->can('Subtract Damage');
    }
}
