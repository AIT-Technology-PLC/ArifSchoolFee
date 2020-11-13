<?php

namespace App\Policies;

use App\Models\Company;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Str;

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
        $doesUserBelongsToCompany = $user->employee->company_id == $company->id;

        if ($doesUserBelongsToCompany) {
            return Str::contains($user->employee->permission->settings, 'crud');
        }

        return false;
    }
}
