<?php

namespace App\Policies;

use App\Models\Damage;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DamagePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Damage');
    }

    public function view(User $user, Damage $damage)
    {
        $doesDamageBelongToMyCompany = $user->employee->company_id == $damage->company_id;

        return $doesDamageBelongToMyCompany && $user->can('Read Damage');
    }

    public function create(User $user)
    {
        return $user->can('Create Damage');
    }

    public function update(User $user, Damage $damage)
    {
        $doesDamageBelongToMyCompany = $user->employee->company_id == $damage->company_id;

        return $doesDamageBelongToMyCompany && $user->can('Update Damage');
    }

    public function delete(User $user, Damage $damage)
    {
        $doesDamageBelongToMyCompany = $user->employee->company_id == $damage->company_id;

        return $doesDamageBelongToMyCompany && $user->can('Delete Damage');
    }

    public function approve(User $user, Damage $damage)
    {
        $doesDamageBelongToMyCompany = $user->employee->company_id == $damage->company_id;

        return $doesDamageBelongToMyCompany && $user->can('Approve Damage');
    }

    public function subtract(User $user, Damage $damage)
    {
        $doesDamageBelongToMyCompany = $user->employee->company_id == $damage->company_id;

        return $doesDamageBelongToMyCompany && $user->can('Subtract Damage');
    }
}
