<?php

namespace App\Policies;

use App\Models\CompanyCompensation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompensationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, CompanyCompensation $companyCompensation)
    {
        return $user->can('Read Compensation');
    }

    public function create(User $user)
    {
        return $user->can('Create Compensation');
    }

    public function update(User $user)
    {
        return $user->can('Update Compensation');
    }

    public function delete(User $user, CompanyCompensation $companyCompensation)
    {
        return $user->can('Delete Compensation');
    }

    public function approve(User $user, CompanyCompensation $companyCompensation)
    {
        return $user->can('Approve Compensation');
    }
}