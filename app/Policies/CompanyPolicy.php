<?php

namespace App\Policies;

use App\Models\Company;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        //
    }

    public function view(User $user, Company $company)
    {
        //
    }

    public function create(User $user)
    {
        //
    }

    public function update(User $user, Company $company)
    {
        //
    }
}
