<?php

namespace App\Policies;

use App\Models\CompanyCompensation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyCompensationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, CompanyCompensation $companyCompensation)
    {
        return $user->can('Read Company Compensation');
    }

    public function create(User $user)
    {
        return $user->can('Create Company Compensation');
    }

    public function update(User $user)
    {
        return $user->can('Update Company Compensation');
    }

    public function delete(User $user, CompanyCompensation $companyCompensation)
    {
        return $user->can('Delete Company Compensation');
    }
}