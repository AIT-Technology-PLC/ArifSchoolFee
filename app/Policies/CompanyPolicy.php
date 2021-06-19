<?php

namespace App\Policies;

use App\Models\Company;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Company $company)
    {
        $doesAdminBelongsToCompany = $user->employee->company_id == $company->id;

        return $doesAdminBelongsToCompany && $user->can('Update Company');
    }
}
