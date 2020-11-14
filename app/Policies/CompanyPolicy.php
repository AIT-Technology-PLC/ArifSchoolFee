<?php

namespace App\Policies;

use App\Models\Company;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Str;

class CompanyPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Company $company)
    {
        $doesAdminBelongsToCompany = $user->employee->company_id == $company->id;

        $isUserAdmin = Str::contains($user->employee->permission->settings, 'crud');

        $canEditCompanyData = $isUserAdmin && $doesAdminBelongsToCompany;

        if ($canEditCompanyData) {
            return true;
        }

        return false;
    }
}
