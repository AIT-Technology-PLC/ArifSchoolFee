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

        $isUserSuperAdmin = $user->employee->permission_id == 1;

        $canEditCompanyData = $isUserSuperAdmin && $doesAdminBelongsToCompany;

        if ($canEditCompanyData) {
            return true;
        }

        return false;
    }
}
